define([
    'jquery',
    'TYPO3/CMS/Backend/Notification',
    'TYPO3/CMS/Backend/DebugConsole'], function ($, Notification, DebugConsole) {

    var NewsImport = function () {
        var me = this;
        var extKey = 'news';
        var runCounter = 0;
        var jobInfo = {};

        me.init = function () {
            $('#jobSelector').on('change', function () {
                var jobClassName = $(this).val();
                // alert(jobClassName);
                if (jobClassName != '0') {
                    me.loadJobInfo(jobClassName);
                } else {
                    Ext.get('job').hide();
                }
            })
        };

        me.loadJobInfo = function (jobClassName) {
//             var request = Ext.apply(me.getBackendRequest();
// // console.log(request);
//             Ext.apply(request, {
//                 success: function (response) {
//                     // jobInfo = (Ext.decode(response.responseText));
//                     console.log(response.responseText);
//                     Ext.apply(jobInfo, {jobClassName: jobClassName});
//                     me.initJob();
//                 }
//             });
//
//             Ext.Ajax.request(request);
            var params = me.getBackendRequest('system', 'tx_news_m1', 'Import', 'jobInfo', {jobClassName: jobClassName});
            $.ajax({
                url: moduleUrl,
                data: params,
                success: function (response) {
                    var r = $.parseJSON(response);
                    if (r.totalRecordCount == 0) {
                        Notification.info('There are no records to be imported!');
                    } else {
                        me.initJob(jobClassName);
                    }
                    console.log(r);
                },
                error: function (response) {
                    var r = $.parseJSON(response.responseText);
                    Notification.error(r.message);
                    console.log(response);
                },
                done: function () {
                    console.log('d1');
                },

            });
        };

        me.getBackendRequest = function (mainModuleName, subModuleName, controller, action, parameters) {
            var parameterPrefix = me.getParameterPrefix(mainModuleName, subModuleName);
            var params = {};

            parameters['controller'] = controller;
            parameters['action'] = action;

            $.each(parameters, function (name, value) {
                params[parameterPrefix + '[' + name + ']'] = value;
            });

            return params;
        };

        me.getParameterPrefix = function (mainModuleName, subModuleName) {
            return 'tx_' + extKey + '_' + mainModuleName + '_' + extKey + subModuleName.replace(/_/g, '');
        };

        me.initJob = function (jobClassName) {
            jobInfo['jobClassName'] = jobClassName;
            $('#job').show();
            $('#progressBar').width('0%').text('fo');
            $('#startButton').on('click', function () {
                runCounter = 0;
                run();
            });
        };
        me.underscoreToUpperCamelCase = function (subject) {
            var matches = subject.match(/(_\w)/g);
            if (matches) {
                matches.each(function (m) {
                    subject = subject.replace(m, m.charAt(1).toUpperCase());
                });
            }
            return subject.charAt(0).toUpperCase() + subject.substr(1);
        };


        me.run = function () {
            var request = Ext.apply(Tx_News.Common.getBackendRequest('system', 'tx_news_m1', 'Import',
                'runJob', {jobClassName: jobInfo.jobClassName, offset: runCounter * jobInfo.increaseOffsetPerRunBy}));

            Ext.apply(request, {
                success: function () {
                    var progress = runCounter / jobInfo.runsToComplete;

                    progressBar.updateProgress(progress, Math.round(100 * progress) + '%');
                    runCounter++;

                    if (runCounter <= jobInfo.runsToComplete) {
                        run();
                    } else {
                        progressBar.updateText('Done.');
                        progressBar.reset();
                        runCounter = 1;
                    }
                },
                failure: function () {
                    alert('error');
                }
            });

            Ext.Ajax.request(request);
        };
    };

    $(document).ready(function () {
        var importer = new NewsImport();
        importer.init();
    });


});