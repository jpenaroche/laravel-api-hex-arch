(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{557:function(e,t,o){"use strict";o.r(t);var n=o(465),l=o(420),r=o(21),d={name:"permisos",layout:"admin",extends:l.a,middleware:"CheckPermissions",meta:{permissions:["listar-permisos"],store_props:{model:r.Permission,fetch:"/admin/permisos"},redirect:"/admin"},components:{DataTable:n.a},data:function(){return{selected_item:{},modal_show:{is_opened:!1},headers:[{sortable:!0,value:"name",showable:!0,in_drawer:!0},{sortable:!0,value:"active",icon:{true:"check_box",false:"check_box_outline_blank"}},{sortable:!0,value:"created_at",showable:!0}],actions:{table:{default:[],plus:[{name:"show",icon:"remove_red_eye",permissions:["show-permisos"]}]},footer:{default:["active"],plus:[]}}}},methods:{openModal:function(e){this.selected_item=e,this.modal_show.is_opened=!0}},computed:{permisos:function(){return r.Permission.query().orderBy("pos").get()}}},c=o(19),v=o(41),_=o.n(v),m=o(144),h=o(143),w=o(417),f=o(126),V=o(472),k=o(128),x=o(138),y=o(146),C=o(139),T=o(129),L=o(130),$=o(145),D=o(149),P=o(411),component=Object(c.a)(d,function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("DataTable",{attrs:{title:e.$tc("permisos.name",0),has_new:!1,section:"permisos",items:e.permisos,headers:e.headers,actions:e.actions},on:{show:e.openModal}}),e._v(" "),o("v-dialog",{attrs:{width:"450",color:"blue"},model:{value:e.modal_show.is_opened,callback:function(t){e.$set(e.modal_show,"is_opened",t)},expression:"modal_show.is_opened"}},[o("v-card",[o("v-card-title",{staticClass:"headline grey lighten-2 text-capitalize",attrs:{"primary-title":""}},[e._v("\n                "+e._s(e.$t("permisos.form.titles.details"))+"\n            ")]),e._v(" "),o("v-card-text",[o("v-layout",{attrs:{row:""}},[o("v-flex",[o("v-list",{attrs:{"two-line":""}},[o("v-list-tile",{attrs:{avatar:""}},[o("v-list-tile-avatar",[o("v-icon",[e._v("\n                                        vpn_key\n                                    ")])],1),e._v(" "),o("v-list-tile-content",[e._v("\n                                    "+e._s(e._f("capitalize")(e.selected_item.name))+"\n                                ")])],1),e._v(" "),o("v-divider"),e._v(" "),o("v-list-tile",{attrs:{avatar:""}},[o("v-list-tile-avatar",[o("v-icon",[e._v("\n                                        date_range\n                                    ")])],1),e._v(" "),o("v-list-tile-content",[e._v("\n                                    "+e._s(e._f("dateFormat")(e.selected_item.created_at,"d-m-Y"))+"\n                                ")])],1)],1)],1)],1),e._v(" "),o("v-card-actions",[o("v-spacer"),e._v(" "),o("v-btn",{attrs:{color:"blue darken-1",flat:""},on:{click:function(t){e.modal_show.is_opened=!1}}},[e._v(e._s(e.$t("buttons.close")))])],1)],1)],1)],1)],1)},[],!1,null,"755a6704",null);t.default=component.exports;_()(component,{VBtn:m.a,VCard:h.a,VCardActions:w.a,VCardText:w.b,VCardTitle:f.a,VDialog:V.a,VDivider:k.a,VFlex:x.a,VIcon:y.a,VLayout:C.a,VList:T.a,VListTile:L.a,VListTileAvatar:$.a,VListTileContent:D.a,VSpacer:P.a})}}]);