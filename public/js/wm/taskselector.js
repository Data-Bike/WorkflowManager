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
    "wm/shorttask",
    "dojo/data/ItemFileReadStore",
    "dojox/grid/DataGrid",
    'dojo/_base/lang',
    'dojox/grid/_RadioSelector',
    "dijit/layout/BorderContainer",
    "dijit/Dialog",
    "dijit/form/Button",
    "dojox/data/JsonRestStore",
    "dojo/io-query",
    "dojo/text!./templates/taskselector.html"
], function(parser, declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        _WidgetsInTemplateMixin, ShortTask, ItemFileReadStore, DataGrid, lang, RadioSelector, BorderContainer, Dialog, Button, JsonRestStore, ioQuery, template) {

    return declare("taskselector", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        widgetInTemplate: true,
        _getValueAttr: function() {
            console.log(this.dr.selection.getSelected());
        },
        postCreate: function() {
            this.inherited(arguments);
            var self = this;
            this.selectButton.on('click', function() {
                console.log(self.dg);
                self.dg.setQuery('?asd');
            });
            this.dg.on('SelectionChanged', function() {
                console.log(self.dg.selection.getSelected());
                self.value=self.dg.selection.getSelected()[0];
            });
            this.dg.on('', function() {
                console.log(self.dg.selection.getSelected());
                alert(0);

            });
            this.st.viewButton.on('click',function(){
                console.log(self.st.getJS());
                console.log(ioQuery.objectToQuery(self.st.getJS()));
                self.dg.setQuery('?'+ioQuery.objectToQuery(self.st.getJS()));
            });
        },
        startup: function() {
            this.inherited(arguments);
            var store = new JsonRestStore({target: '/api/v1/'});
            this.dg.setStore(store);
        }
    });
    parser.parse();
});