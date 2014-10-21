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
    "dojo/_base/declare",
    "dijit/_WidgetBase",
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin",
    "dijit/layout/AccordionContainer",
    "dijit/layout/ContentPane",
    "dijit/layout/BorderContainer",
    "dijit/form/FilteringSelect",
    "dojo/store/Memory",
    "dojo/store/Observable",
    "dojo/data/ObjectStore",
    "dijit/form/Button",
    "dojox/grid/DataGrid",
    "dojo/text!./templates/userslist.html"
], function (declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, AccordionContainer, ContentPane, BorderContainer, FilteringSelect, Memory, Observable, ObjectStore, Button, DataGrid, template) {

    return declare("userslist", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        userStore: '',
        taskStore: '',
        formatter: function () {
            var w = new dijit.form.Button();
            w._destroyOnRemove = true;
            return w;
        },
        addUser: function () {

        },
        _getValueAttr: function () {
            var value = '';
            var obj = {};
            var data = this.userStore1.fetch().store._dirtyObjects;
            console.log(data);
            for (var key in data) {
                if (data[key] && data[key].object && data[key].object.id) {
                    obj = data[key].object;
                    value += value ? ',' : '';
                    value += obj.id;
                }
            }
            return value;
        },
        _setValueAttr: function (value) {
            this.setJS(value);
        },
        setJS: function (js) {
            this.userStore = new Observable(new Memory({data: js}));
            var self = this;
            this.userStore1 = new ObjectStore({objectStore: self.userStore});
            this.dg.setStore(this.userStore1);
        },
        postCreate: function () {
            this.inherited(arguments);
            var self = this;

            this.userStore = new Observable(new Memory());
            this.userStore1 = new ObjectStore({objectStore: self.userStore});
            this.dg = new DataGrid({
                store: self.userStore1,
                style: {width: '90%', height: '100px'},
                structure: [
                    {name: 'ФИО', field: 'name'},
                    {name: 'Должность', field: 'position'},
                    {name: ' ', field: '_item', formatter: function (item) {
                            return new Button({onClick: function () {
                                    self.userStore1.deleteItem(item);
                                }});
                        }}
                ]
            }).placeAt(this.grid);
            this.dg.startup();
            this.dg.setStore(this.userStore1);
            this.select.store = this.userStore;
            this.addUserButton.onClick = function () {
                self.userStore1.newItem(self.select.item);
            };
        }
    });
});