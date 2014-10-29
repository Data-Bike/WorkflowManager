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
    "dojo/text!./templates/user.html"
], function (parser, declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, Button, TextBox, Textarea, TimeTextBox, DateTextBox, ContentPane, BorderContainer, JsonRest, taskslist, userslist, Memory, Cache, Stamp, Date, template) {

    return declare("user", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        name: '',
        login: '',
        email: '',
        position: '',
        bd: '',
        userId: undefined,
        password: '',
        username: '',
        state: '',
        _getValueAttr: function () {
            return this.getJS();
        },
        _setValueAttr: function (value) {
            return this.setJS(value);
        },
        getJS: function () {

            return {
                username: this.username.get('value') ? this.username.get('value') : undefined,
                password: this.password.get('value') ? this.password.get('value') : undefined,
                name: this.name.get('value') ? this.name.get('value') : undefined,
                email: this.email.get('value') ? this.email.get('value') : undefined,
                position: this.position.get('value') ? this.position.get('value') : undefined,
                state: this.state.get('value') ? this.state.get('value') : undefined,
                chefList: this.chefList.get('value'),
                memberList: this.memberList.get('value'),
                executeList: this.executeList.get('value'),
                curateList: this.curateList.get('value'),
                id: this.userId
            };
        },
        setJS: function (data) {
            this.username.set('value', data.username);
            this.state.set('value', data.state);
            this.name.set('value', data.name);
            this.email.set('value', data.email);
            this.position.set('value', data.position);
            this.chefList.set('value', data.chefList);
            this.memberList.set('value', data.memberList);
            this.executeList.set('value', data.executeList);
            this.curateList.set('value', data.curateList);
        },
        postCreate: function () {
            this.inherited(arguments);

            var storeRest = new JsonRest({target: '/user'});
            var storeMemory = new Memory();
            var userCache = new Cache(storeRest, storeMemory);

            this.chefList.select.store = userCache;
            this.memberList.select.store = userCache;

        }
    });

    parser.parse();

});