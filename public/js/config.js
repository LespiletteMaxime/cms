var require = {
    baseUrl: '/packages/devise/cms/js/lib',
    //urlArgs: "bust=" + (new Date()).getTime(),
    shim:  {
        ckeditorJquery : {
            deps: ['jquery', 'ckeditorCore']
        },
        datetimepicker : {
            deps: ['jquery'],
            exports: '$.fn.datetimepicker'
        }
    },
    paths: {
        jquery:             '../lib/jquery',
        'jquery-ui':        '../lib/jquery-ui',
        jqueryEasing:       '../lib/jquery.easing.1.3',
        ckeditorCore:       '../lib/ckeditor/ckeditor',
        ckeditorJquery:     '../lib/ckeditor/adapters/jquery',
        spectrum:           '../lib/spectrum',
        datetimepicker:     '../lib/jquery.datetimepicker',
        moment:             '../lib/moment',
        jcrop :             '../lib/jquery.Jcrop.min',
        async:              '../lib/millermedeiros-plugins/async',
        goog:               '../lib/millermedeiros-plugins/goog',
        propertyParser :    '../lib/millermedeiros-plugins/propertyParser',
        app:                '../app',
        dvsEditor:          '../app/devise',
        dvsPublic:          '../app/public/preview',
        dvsInitialize:      '../app/helpers/initialize-editor',
        dvsListeners:       '../app/helpers/listeners',
        dvsNetwork:         '../app/helpers/network',
        dvsNodeView:        '../app/nodes/node-view',
        dvsAdmin:           '../app/admin/admin',
        dvsSidebarView:     '../app/sidebar/sidebar-view',
        dvsAdminView:       '../app/admin/admin-view',
        dvsAdminPages:      '../app/admin/pages',
        dvsLiveUpdate:      '../app/helpers/live-update',
        dvsReplacement:     '../app/helpers/replacement',
        dvsModal:           '../app/helpers/modal',
        dvsMessageBus:      '../app/helpers/global-bus',
        dvsRouter:          '../app/routes',
        dvsQueryHelper:     '../app/helpers/query-helper',
        dvsDatePicker:      '../app/helpers/date-picker',
        dvsSelectSurrogate: '../app/helpers/forms-select-surrogate'

    },
    devise: {
        partialLoaderPath:  '/admin/partialLoader'
    },
};

var CKEDITOR_BASEPATH = "/packages/devise/cms/js/lib/ckeditor/";
