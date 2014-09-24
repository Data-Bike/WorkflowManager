/* 
 * Copyright (c) 2014, newworld
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
    "wm/task",
    "dijit/layout/AccordionContainer",
    "dijit/layout/ContentPane",
    "dojo/text!./templates/taskslist.html"
], function(declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
            _WidgetsInTemplateMixin, task, AccordionContainer, ContentPane, template) {
 
    return declare("taskslist", [_WidgetBase, _OnDijitClickMixin,
        _TemplatedMixin, _WidgetsInTemplateMixin
    ], {
        templateString: template,
        selected:{},
        onSelect:function(js){
            console.log(js);
        },
        clear:function(){
            var childs=this.list.getChildren();
            for (var child in childs){
                this.list.removeChild(childs[child]);
            }
        },
        setJS:function(objs){
            this.clear();
            var self=this;
            for(var obj in objs){
                var newtask=new task(objs[obj]);
                self.list.addChild(new ContentPane({
                    content:newtask,
                    title:objs[obj].name,
                    JS:objs[obj],
                    onSelected:function(){
                        self.selected=this.JS;
                        self.onSelect(this.JS);
                    }
                }));
                newtask.setJS(obj);
            }
        }
    });
 
});