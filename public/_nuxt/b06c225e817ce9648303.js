(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{441:function(t,e,n){"use strict";var o={name:"modal-action",props:{title:String,text:String,actions:{type:Object,defaut:{cancel:{},confirm:{}}},is_persisted:{type:Boolean,default:!0},is_opened:{type:Boolean,default:!1},is_sending:{type:Boolean,default:!1}}},r=n(19),c=n(41),d=n.n(c),l=n(144),f=n(143),m=n(417),v=n(126),_=n(472),h=n(138),$=n(146),x=n(139),w=n(109),k=n(411),component=Object(r.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-dialog",{attrs:{persistent:"",width:"350"},model:{value:t.is_opened,callback:function(e){t.is_opened=e},expression:"is_opened"}},[n("v-card",[n("v-card-title",{staticClass:"headline"},[t._v(t._s(t.title))]),t._v(" "),n("v-card-text",[n("v-layout",{attrs:{row:""}},[n("v-flex",{attrs:{xs3:"",sm3:""}},[t.is_sending?n("v-progress-circular",{attrs:{size:40,color:"amber",indeterminate:""}}):n("v-icon",{attrs:{large:"",color:"yellow darken-3"}},[t._v("\n                        warning\n                    ")])],1),t._v(" "),n("v-flex",[t._v("\n                    "+t._s(t.text)+"\n                ")])],1)],1),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{disabled:t.is_sending,color:"green darken-1",flat:"flat"},on:{click:function(e){return e.stopPropagation(),t.actions.cancel()}}},[t._v("\n                "+t._s(t.$t("buttons.cancel"))+"\n            ")]),t._v(" "),n("v-btn",{attrs:{disabled:t.is_sending,color:"green darken-1",flat:"flat"},on:{click:function(e){return e.stopPropagation(),t.actions.confirm()}}},[t._v("\n                "+t._s(t.$t("buttons.confirm"))+"\n\n            ")])],1)],1)],1)},[],!1,null,"f7d925de",null);e.a=component.exports;d()(component,{VBtn:l.a,VCard:f.a,VCardActions:m.a,VCardText:m.b,VCardTitle:v.a,VDialog:_.a,VFlex:h.a,VIcon:$.a,VLayout:x.a,VProgressCircular:w.a,VSpacer:k.a})},468:function(t,e,n){"use strict";n(57),n(20);var o=n(428),r=n(419),c=n(441),d={name:"edit-form",extends:r.a,components:{BaseForm:o.a,ModalAction:c.a},props:{data:Object,section:String,title:String},created:function(){var t=this;["updated","deleted"].forEach(function(e){t.$bus.$on(e,function(){setTimeout(function(){t.$router.replace("".concat(t.routes.base.admin,"/").concat(t.section))},t.mixin.admin.notification.timeout)})}),this.$bus.$on("cantDelete",function(){return t.cant_delete=!0})},data:function(){var t=this;return{modal:{opened:!1,actions:{cancel:function(){t.modal.opened=!1},confirm:{}}},repository:this.$repositories[this.section],cant_delete:!1}},methods:{openModal:function(){this.modal.opened=!0,this.modal.actions.confirm=this.destroy.bind(this,this.data.id)}}},l=n(19),f=n(41),m=n.n(f),v=n(144),_=n(146),h=n(474),component=Object(l.a)(d,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("nuxt-link",{attrs:{to:this.routes.base.admin+"/"+t.section+"/create"}},[n("v-btn",{attrs:{flat:"",color:"teal"}},[t._v("\n            "+t._s(t.$t("buttons.new"))+"\n            "),n("v-icon",[t._v("\n                add\n            ")])],1)],1),t._v(" "),n("base-form",{attrs:{link_to_list:this.routes.base.admin+"/"+t.section,title:t._f("capitalize")(t.$t("keywords.edit")+" "+t.title),icon_action:"edit"}},[n("template",{slot:"form"},[n("modal-action",{attrs:{actions:t.modal.actions,is_opened:t.modal.opened,title:t.$t(t.section+".dialogs.delete.title"),text:t.$tc(t.section+".dialogs.delete.text",1),is_sending:t.isSending}}),t._v(" "),t._t("form")],2),t._v(" "),n("template",{slot:"actions"},[t._t("actions"),t._v(" "),n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(data){return[n("v-btn",t._g({directives:[{name:"can",rawName:"v-can.disabled",value:["actualizar-"+t.section],expression:"[`actualizar-${section}`]",modifiers:{disabled:!0}}],attrs:{disabled:t.isSending,loading:t._f("whoIsSending")(t.section+"/updating"),fab:"",color:"success"},on:{click:function(e){return t.$emit("update")}}},data.on),[n("v-icon",[t._v("\n                            save\n                        ")])],1)]}}])},[t._v(" "),n("span",[t._v(t._s(t.$t("buttons.tooltips.save")))])]),t._v(" "),n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(data){return[n("v-btn",t._g({directives:[{name:"can",rawName:"v-can.disabled",value:["borrar-"+t.section],expression:"[`borrar-${section}`]",modifiers:{disabled:!0}}],attrs:{disabled:t.isSending||t.cant_delete,loading:t._f("whoIsSending")(t.section+"/deleting"),absolute:"",fab:"",left:"",color:"error"},on:{click:t.openModal}},data.on),[n("v-icon",[t._v("\n                            delete\n                        ")])],1)]}}])},[t._v(" "),n("span",[t._v(t._s(t.$t("buttons.delete")))])])],2)],2)],1)},[],!1,null,"32794f16",null);e.a=component.exports;m()(component,{VBtn:v.a,VIcon:_.a,VTooltip:h.a})},564:function(t,e,n){"use strict";n.r(e);n(42);var o=n(419),r=n(468),c=n(479),d=n(25),l={layout:"admin",middleware:"CheckPermissions",extends:o.a,components:{UserForm:c.a,EditForm:r.a},validate:function(t){var e=t.params;return/^\d+$/.test(e.id)},meta:{permissions:["editar-usuarios"],store_props:{model:d.User,query:"query().with('profile|roles|permissions').find(route.params.id)",fetch:"admin/usuarios",dependencies:{Role:"admin/roles",Permission:"admin/permisos"}},data_var:"user",redirect:"/admin/usuarios"},data:function(){return{show_pass:!1,repository:this.$repositories.usuarios,clone:{},files:[]}},created:function(){Object.assign(this.clone,this.user)},methods:{go:function(){this.update(this.clone.id,this.$refs.user_form.getData())}}},f=n(19),component=Object(f.a)(l,function(){var t=this.$createElement,e=this._self._c||t;return e("edit-form",{attrs:{data:this.clone,title:this.$tc("usuarios.name",1),section:"usuarios"},on:{update:this.go}},[e("template",{slot:"form"},[e("user-form",{ref:"user_form",attrs:{user:this.clone,files:this.files}})],1)],2)},[],!1,null,"507ed264",null);e.default=component.exports}}]);