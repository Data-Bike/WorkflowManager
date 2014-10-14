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
    "dijit/layout/AccordionContainer",
    "dijit/layout/ContentPane",
    "dijit/layout/BorderContainer",
    "dijit/form/FilteringSelect",
    "dojo/store/Memory",
    "dijit/Dialog",
    "dojo/store/Observable",
    "dojo/data/ObjectStore",
    "dijit/form/Button",
    "dojox/grid/DataGrid",
    "dojo/text!./templates/taskslist.html"
], function (parser, declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, AccordionContainer, ContentPane, BorderContainer, FilteringSelect, Memory, Dialog, Observable, ObjectStore, Button, DataGrid, template) {

    return declare("userslist", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        userStore: '',
        taskStore: '',
        addUser: function () {

        },
        _getValueAttr: function () {
            var value = '';
            var obj = {};
            var data = this.taskStore1.fetch().store._dirtyObjects;
            console.log(data);
            for (var key in data) {
                obj = data[key].object;
                value += value ? ',' : '';
                value += obj.id;
            }
            return value;
        },
        _setValueAttr: function (value) {
            this.setJS(value);
        },
        setJS: function (js) {
            this.taskStore = new Observable(new Memory({data:js}));
            var self = this;
            this.taskStore1 = new ObjectStore({objectStore: self.taskStore});
            this.dg.setStore(this.taskStore1);
        },
        postCreate: function () {

            this.inherited(arguments);
            this.value = '';

            this.taskStore = new Observable(new Memory());
            var self = this;
            this.taskStore1 = new ObjectStore({objectStore: self.taskStore});
            this.dg = new DataGrid({
                store: self.taskStore1,
                style: {width: '90%', height: '100px'},
                structure: [
                    {name: 'Название', field: 'Name'},
                    {name: 'Описание', field: 'about'},
                    {name: ' ', field: '_item', formatter: function (item) {
                            return new Button({onClick: function () {
                                    self.taskStore1.deleteItem(item);
                                }});
                        }}
                ]
            }).placeAt(this.grid);
            this.dg.startup();

            this.selectButton.onClick = function () {
                self.dialog.show();
            };
            this.ts.selectButton.on('click', function () {
                self.selected = self.ts.value;
                console.log(self.selected.params.data);
                self.taskStore1.newItem(self.selected.params.data);
                self.value = self.value ? self.value + ',' + self.ts.value.id : self.value + self.ts.value.id;
                self.dialog.hide();
            });
        }
    });

    parser.parse();

});