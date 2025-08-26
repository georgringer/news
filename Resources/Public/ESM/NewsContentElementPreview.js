import Modal from '@typo3/backend/modal.js';
import Severity from '@typo3/backend/severity.js';
import {lll} from '@typo3/core/lit-helper.js';
import {html} from 'lit';

document.querySelectorAll('[data-news-content-element-preview]').forEach(function (button) {
    button.addEventListener('click', function (ev) {
        Modal.advanced({
            content: html`
                <p class="lead">${lll('newsContentElement.preview.modal.lead')}</p>

                <div class="card-container">
                    <div class="card card-size-small">
                        <div class="card-header">
                            <div class="card-header-body">
                                <h3 class="card-title">${lll('newsContentElement.preview.modal.line1')}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">${lll('newsContentElement.preview.modal.line2')}</p>
                        </div>
                    </div>
                    <div class="card card-size-small">
                        <div class="card-header">
                            <div class="card-header-body">
                                <h3 class="card-title">${lll('newsContentElement.preview.modal.line3')}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">${lll('newsContentElement.preview.modal.line4')}</p>
                        </div>
                    </div>
                </div>
                
                <h4>${lll('newsContentElement.preview.modal.line5')}</h4>
                ${lll('newsContentElement.preview.modal.line6')}
                <p>
                    <br>
                    <a href="https://docs.typo3.org/p/georgringer/news/main/en-us/Addons/NewsContentElements/Index.html"
                       target="_blank" class="btn btn-primary">🚀 ${lll('newsContentElement.preview.modal.link')}</a>
                </p>
            `,
            severity: Severity.info,
            title: lll('newsContentElement.preview.modal.title'),
            size: Modal.sizes.medium,
            buttons: [
                {
                    text: TYPO3?.lang?.['button.ok'] || 'Ok',
                    name: 'ok',
                    active: true,
                    btnClass: 'btn-default',
                    trigger: function (e, modal) {
                        modal.hideModal();
                    }
                }
            ]
        });
    });
});