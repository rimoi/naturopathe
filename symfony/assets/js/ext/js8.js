(appTheme=appTheme||{}).FixedComponents=function(){function t(){this.$body=$("body"),this.$menu=$("#menu"),this.$topBar=$("#top-bar"),this.fixedMenuEnabled=this.$body.hasClass("menu_fixed"),this.fixedTopBarEnabled=this.$body.hasClass("top_bar_fixed"),this.$menu.length&&(this.menuOffset=this.$menu.offset(),this.menuOffsetTop=this.menuOffset.top,this.menuHeight=this.$menu[0].offsetHeight),this.$topBar.length&&(this.topBarOffset=this.$topBar.offset(),this.topBarOffsetTop=this.topBarOffset.top,this.topBarHeight=this.$topBar[0].offsetHeight),this.init()}return t.prototype={fixMenu:function(t){this.fixedMenuEnabled&&(t>this.getMenuOffsetTop()?this.$menu.css({position:"fixed",top:this.fixedTopBar()&&this.topBarHeight?this.topBarHeight:0,left:0,right:0,zIndex:7200},100).addClass("menu_is_fixing"):this.$menu.css({position:"static"}).removeClass("menu_is_fixing"),this.setPaddingTop())},fixTopBar:function(t){this.fixedTopBarEnabled&&(t>this.topBarOffsetTop?this.$topBar.css({position:"fixed",top:0,left:this.topBarOffset.left,width:this.$topBar[0].offsetWidth,zIndex:7200},100).addClass("top_bar_is_fixing"):this.$topBar.css({position:"static"}).removeClass("top_bar_is_fixing"),this.setPaddingTop())},fixComponents:function(){var t=this;$(document).scroll(function(){var i=$(window).scrollTop();t.fixMenu(i),t.fixTopBar(i)})},init:function(){(this.fixedMenuEnabled||this.fixedTopBarEnabled)&&this.fixComponents()},fixedMenu:function(){return this.$menu.hasClass("menu_is_fixing")},fixedTopBar:function(){return this.$topBar.hasClass("top_bar_is_fixing")},getMenuOffsetTop:function(){var t=this.menuOffsetTop;return this.fixedTopBar()&&(t-=this.topBarHeight),t},setPaddingTop:function(){paddingTop=0,this.fixedTopBar()&&(paddingTop+=this.topBarHeight),this.fixedMenu()&&(paddingTop+=this.menuHeight),$("#body-container-inner").css("padding-top",paddingTop)}},t}(),(appTheme=appTheme||{}).Header2=function(){function t(){this.$html=$("#header2"),this.init()}return t.prototype={init:function(){this.adaptHeightToWindow()},adaptHeightToWindow:function(){$("body").hasClass("adapt_header2_height_to_window")&&this.$html.find(".sb-bloc-level-3:first").css("height",$(window).height()-this.$html.offset().top)}},t}();var appTheme=appTheme||{};$(document).on("ready page:load",function(){var t=new appVisitor.Router;$(window).width()>=992&&(t.isContextContent()||t.isContextPages()||(appTheme.fixedComponents=new appTheme.FixedComponents),appTheme.header2=new appTheme.Header2)});