define([
    'jquery',
    'TYPO3/CMS/Backend/Notification'], function ($, Notification) {

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
                    $('#job').hide();
                }
            })
        };

        me.loadJobInfo = function (jobClassName) {
            var params = me.getBackendRequest('system', 'tx_news_m1', 'Import', 'jobInfo', {jobClassName: jobClassName});
            $.ajax({
                url: moduleUrl,
                data: params,
                success: function (response) {
                    var r = $.parseJSON(response);
                    if (r.totalRecordCount == 0) {
                        Notification.info('There are no records to be imported!');
                    } else {
                        jobInfo = r;
                        me.initJob(jobClassName);
                    }
                },
                error: function (response) {
                    var r = $.parseJSON(response.responseText);
                    Notification.error(r.message);
                }
            });
        };

        me.initJob = function (jobClassName) {
            jobInfo['jobClassName'] = jobClassName;
            $('#job').show();
            $('#progressBar').width('0%').text('fo');
            $('#startButton').on('click', function () {
                runCounter = 0;
                me.run();
            });
        };

        me.run = function () {
            var params = me.getBackendRequest('system', 'tx_news_m1', 'Import', 'runJob', {
                jobClassName: jobInfo.jobClassName,
                offset: runCounter * jobInfo.increaseOffsetPerRunBy
            });
            $.ajax({
                url: moduleUrl,
                data: params,
                success: function (response) {
                    var progress = runCounter / jobInfo.runsToComplete;
                    var progressValue = Math.round(100 * progress) + '%';
                    $('#progressBar').width(progressValue).text(progressValue);
                    runCounter++;

                    if (runCounter <= jobInfo.runsToComplete) {
                        me.run();
                    } else {
                        $('#progressBar').text('Done!');
                        $('#news-import-form').hide();
                        $('#job').hide();
                        $('#news-import-done').show();
                        console.log('done');
                        runCounter = 1;
                    }

                },
                error: function (response) {
                    var r = $.parseJSON(response.responseText);
                    Notification.error(r.message);
                },
                done: function () {
                    console.log('d1');
                }
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

        me.underscoreToUpperCamelCase = function (subject) {
            var matches = subject.match(/(_\w)/g);
            if (matches) {
                matches.each(function (m) {
                    subject = subject.replace(m, m.charAt(1).toUpperCase());
                });
            }
            return subject.charAt(0).toUpperCase() + subject.substr(1);
        };

        me.getParameterPrefix = function (mainModuleName, subModuleName) {
            return 'tx_' + extKey + '_' + mainModuleName + '_' + extKey + subModuleName.replace(/_/g, '');
        };

    };

    $(document).ready(function () {
        var importer = new NewsImport();
        importer.init();
    });
});
