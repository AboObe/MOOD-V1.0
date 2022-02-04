<div class="left main-sidebar">
	
		<div class="sidebar-inner leftscroll">

			<div id="sidebar-menu">
        
			<ul>

					<li class="submenu">
						<a href="{{ url('/home') }}"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
                @if(Auth::user()->type == 'admin')
					<li class="submenu">
						<a href="{{ route('web_user.index') }}"><i class="fa fa-user-o bigfonts"></i> <span> USERS </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_restaurant.index') }}"><i class="fa fa-fw fa-th"></i> <span> RESTAURANTS </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_service.index') }}"><i class="fa fa-coffee bigfonts"></i> <span> SERVICES </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_offer.index') }}"><i class="fa fa-fire bigfonts"></i> <span> OFFERS </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_campaign.index') }}"><i class="fa fa-diamond bigfonts"></i> <span> CAMPAIGNS </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_tag.index') }}"><i class="fa fa-tags bigfonts"></i> <span> TAGS </span> </a>
                    </li>
                    <!--<li class="submenu">
						<a href="{{ route('web_status.index') }}"><i class="fa fa-fw fa-th"></i> <span> STATUSES </span> </a>
                    </li>-->
                    <li class="submenu">
						<a href="{{ route('web_category.index') }}"><i class="fa fa-fw fa-th"></i> <span> CATEGORY </span> </a>
                    </li>
                <!--    <li class="submenu">
						<a href="{{ route('web_media.index') }}"><i class="fa fa-photo bigfonts"></i> <span> MEDIA </span> </a>
                    </li> -->
                    <li class="submenu">
                        <a href="{{ route('web_user.form_send_notification') }}"><i class="fa fa-send bigfonts"></i> <span> Notification </span> </a>
                    </li>


                @elseif(Auth::user()->type == 'restaurant_manager')
                	<li class="submenu">
						<a href="{{ route('web_restaurant.my_restaurent', Auth::user()->id ) }}"><i class="fa fa-fw fa-th"></i> <span> RESTAURANTS </span> </a>
                    </li>
                    <li class="submenu">
						<a href="{{ route('web_my_offer.index') }}"><i class="fa fa-fire bigfonts"></i> <span> OFFERS </span> </a>
                    </li>

				@endif
            </ul>

            <div class="clearfix"></div>

			</div>
        
			<div class="clearfix"></div>

		</div>

	</div>
