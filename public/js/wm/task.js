/* 
 * Copyright (c) 2014, Alexander Platonov
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
define([
    "dojo/parser",
    "dojo/_base/declare",
    "dijit/_WidgetBase",
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin",
    "dijit/form/Button",
    "dijit/form/TextBox",
    "dijit/form/Textarea",
    "dijit/form/TimeTextBox",
    "dijit/form/DateTextBox",
    "dijit/layout/ContentPane",
    "dijit/layout/BorderContainer",
    "dojo/store/JsonRest",
    "wm/taskslist",
    "wm/userslist",
    "dojo/store/Memory",
    "dojo/store/Cache",
    "dojo/date/stamp",
    "dojo/date",
    "dojo/text!./templates/task.html"
], function(parser, declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, Button, TextBox, Textarea, TimeTextBox, DateTextBox, ContentPane, BorderContainer, JsonRest, taskslist, userslist, Memory, Cache, Stamp, Date, template) {

    return declare("task", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        code: 'задача не создана',
        name: 'укажите имя',
        about: 'укажите информацию о задаче',
        taskId: undefined,
        startDate: 0,
        startTime: 0,
        finishDate: 0,
        finishTime: 0,
        __getAttrValue: function() {
            return this.getJS();
        },
        __setAttrValue: function() {
            return this.setJS();
        },
        getJS: function() {
            var startDateTime = undefined;
            var startDate = this.startDate.get('value');
            if (startDate) {
                startDateTime = Date.add(startDate, 'hour', this.startTime.get('value').getHours());
                startDateTime = Date.add(startDateTime, 'minute', this.startTime.get('value').getMinutes());
                startDateTime = startDateTime.toUTCString();
            }
            
            var finishDateTime = undefined;
            var finishDate = this.finishDate.get('value');
            if (finishDate) {
                var finishDateTime = Date.add(finishDate, 'hour', this.finishTime.get('value').getHours());
                finishDateTime = Date.add(finishDateTime, 'minute', this.finishTime.get('value').getMinutes());
                finishDateTime = finishDateTime.toUTCString();
            }
            return {
                name: this.name.get('value'),
                about: this.about.get('value'),
                startDateTime: startDateTime,
                finishDateTime: finishDateTime,
                executorsList: this.executorsList.get('value'),
                curatorsList: this.curatorsList.get('value'),
                necessaryList: this.necessaryList.get('value'),
                sufficientlyList: this.sufficientlyList.get('value'),
                consequenceList: this.consequenceList.get('value'),
                id: this.taskId
            };
        },
        setJS: function(data) {
            this.name.set('value', data.name);
            this.about.set('value', data.about);
            this.startDate.set('value', data.startDate);
            this.startTime.set('value', data.startTime);
            this.finishDate.set('value', data.finishDate);
            this.finishTime.set('value', data.finishTime);
            this.executorsList.set('value', data.executorsList);
            this.curatorsList.set('value', data.curatorsList);
            this.necessaryList.set('value', data.necessaryList);
            this.sufficientlyList.set('value', data.sufficientlyList);
            this.consequenceList.set('value', data.consequenceList);
            this.code = data.code;
        },
        postCreate: function() {
            this.inherited(arguments);

            var store = new JsonRest({target: '/'});
            store.query('user');
            this.executorsList.select.store = store;
            this.curatorsList.select.store = store;
        }
    });

    parser.parse();

});