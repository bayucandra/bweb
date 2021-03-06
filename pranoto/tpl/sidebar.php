	<section id="secondary_bar">
		<div class="user">
			<p>{$admin_full_name}</p>
			<a class="logout_user" href="?admin_logout=1" title="Logout">Logout</a>
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="javascript:void(0);">Admin Page</a> <div class="breadcrumb_divider"></div> <a class="current">{$current_page_name}</a></article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column" style="height:900px;">
		<!--
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="{literal}if(!this._haschanged){this.value=''}{/literal};this._haschanged=true;">
		</form>
		-->
		<hr/>
		<h3>Content</h3>
		<ul class="toggle">
			<li class="icn_photo"><a href="?act=slider">Slider</a></li>
			<li class="icn_edit_article"><a href="?act=pages">Pages</a></li>
			<li class="icn_categories"><a href="?act=products">Products</a></li>
			<li class="icn_tags"><a href="?act=tools">Tools</a></li>
		</ul>
		<h3>Users And Account</h3>
		<ul class="toggle">
			<li class="icn_view_users"><a href="#">Member</a></li>
			<li class="icn_view_users"><a href="#">Admin</a></li>
		</ul>
		<h3>Admin</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="?act=option">Options</a></li>
			<li class="icn_jump_back"><a href="?admin_logout=1">Logout</a></li>
		</ul>
		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2013 PT.WISANKA</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
