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
    "wm/task",
    "dojo/data/ItemFileReadStore",
    "dojox/grid/DataGrid",
    'dojo/_base/lang',
    "dojo/text!./templates/taskselector.html"
], function(parser, declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, Task, ItemFileReadStore, DataGrid, lang, template) {

    return declare("taskselector", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        layout: [
            {type: 'dojox.grid._RadioSelector'},
            [
                {name: 'Название', width: '10%', field: 'name'},
                {name: 'Описание', width: '10%', field: 'about'},
                {name: 'Начало выполнения', width: '10%', field: 'start'},
                {name: 'Окончание выполнения', width: '10%', field: 'finish'},
                {name: 'Исполнители', width: '10%', field: 'executors'},
                {name: 'Кураторы', width: '10%', field: 'curators'},
                {name: 'Необходимо для выполнения', width: '10%', field: 'necessity'},
                {name: 'Достаточно для выполнения', width: '10%', field: 'sufficiency'}

            ]
        ],
        getJS: function() {
            return {
                name: this.name.get('value'),
                about: this.about.get('value'),
                startDate: this.startDate.get('value'),
                startTime: this.startTime.get('value'),
                finishDate: this.finishDate.get('value'),
                finishTime: this.finishTime.get('value'),
                code: this.code
            };
        },
        setJS: function(data) {
            this.name.set('value', data.name);
            this.about.set('value', data.about);
            this.startDate.set('value', data.startDate);
            this.startTime.set('value', data.startTime);
            this.finishDate.set('value', data.finishDate);
            this.finishTime.set('value', data.finishTime);
            this.code = data.code;
        },
        myGrid:{},
        postCreate: function() {
            this.inherited(arguments);
            var self = this;
//            this.dataGrid.getContactName = function(colIndex, item) {
//                alert('adsad');
//                return item.name;
//            };
            
            var data_list = [
                {name: '2010-01-01', about: '', start: '', finish: '', executors: '', curators: '', necessity: '', suficiency: ''},
                {name: '2011-03-04', about: '', start: '', finish: '', executors: '', curators: '', necessity: '', suficiency: ''},
                {name: '2011-03-08', about: '', start: '', finish: '', executors: '', curators: '', necessity: '', suficiency: ''},
                {name: '2007-02-14', about: '', start: '', finish: '', executors: '', curators: '', necessity: '', suficiency: ''},
                {name: '2008-12-26', about: '', start: '', finish: '', executors: '', curators: '', necessity: '', suficiency: ''}
            ];
            var data = {
                identifier: "id",
                items: []
            };

            var rows = 60;
            for (var i = 0, l = data_list.length; i < rows; i++) {
                data.items.push(lang.mixin({id: i + 1}, data_list[i % l]));
            }

            var store = new ItemFileReadStore({
                data: data
            });
//            this.dataGrid.store = tasksStore;
//            self.structure = [
//                {type: 'dojox.grid._RadioSelector'},
//                [
//                    {name: 'Название', width: '10%', field: 'name'},
//                    {name: 'Описание', width: '10%', field: 'about'},
//                    {name: 'Начало выполнения', width: '10%', field: 'start'},
//                    {name: 'Окончание выполнения', width: '10%', field: 'finish'},
//                    {name: 'Исполнители', width: '10%', field: 'executors'},
//                    {name: 'Кураторы', width: '10%', field: 'curators'},
//                    {name: 'Необходимо для выполнения', width: '10%', field: 'necessity'},
//                    {name: 'Достаточно для выполнения', width: '10%', field: 'sufficiency'}
//
//                ]
//            ];
            this.store=store;
//            this.dataGrid.structure = this.layout;
            this.dataGrid.setStore(store);
            this.dataGrid.id = 'grid';
            this.dataGrid.rowSelector = '20px';
//            this.dataGrid.render();
            console.log(this.dataGrid);
        }
    });
    parser.parse();
});