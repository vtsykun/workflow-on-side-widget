define(function(require) {
    'use strict';

    var AssignedTasksContentView;
    var $ = require('jquery');
    var mediator = require('oroui/js/mediator');
    var routing = require('routing');
    var LoadingMask = require('oroui/js/app/views/loading-mask-view');
    var BaseView = require('oroui/js/app/views/base/view');
    var ComponentManager = require('oroui/js/app/components/component-manager');

    AssignedTasksContentView = BaseView.extend({
        defaultPerPage: 5,

        template: require('tpl!orotask/templates/sidebar-widget/assigned-tasks/assigned-tasks-content-view.html'),

        events: {
            'click .task-widget-row': 'onClickTask'
        },

        listen: {
            refresh: 'reloadTasks'
        },

        render: function() {
            console.log('push test');
            this.reloadTasks();
            this.initPageComponents();
            return this;
        },


        /**
         * Initializes all linked page components
         * @param {Object|null} options
         */
        initPageComponents: function(options) {
            return this._getComponentManager().init(options);
        },


        /**
         * Getter for component manager
         *
         * @returns {ComponentManager}
         */
        _getComponentManager: function() {
            if (!this.componentManager) {
                let layout = this.getLayoutElement();
                console.log(layout.find('.pull-left'));
                this.componentManager = new ComponentManager(this.getLayoutElement());
            }
            return this.componentManager;
        },

        onClickTask: function(event) {
            this.initPageComponents();
        },

        reloadTasks: function() {
            this.initPageComponents();
            var view = this;
            var settings = this.model.get('settings');
            settings.perPage = settings.perPage || this.defaultPerPage;

            var routeParams = {
                perPage: settings.perPage,
                r: Math.random()
            };
            var url = routing.generate('oro_task_widget_sidebar_tasks', routeParams);

            var loadingMask = new LoadingMask({
                container: view.$el
            });
            loadingMask.show();

            $.get(url, function(content) {
                loadingMask.dispose();
                view.$el.html(view.template({'content': content}));
            });
        }
    });

    return AssignedTasksContentView;
});
