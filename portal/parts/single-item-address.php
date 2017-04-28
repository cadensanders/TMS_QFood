<div class="address-container {if defined('AIT_GET_DIRECTIONS_ENABLED')}get-direction-enabled{/if}">
	<h2>{__ 'Address & Contact'}</h2>
	<div class="content">
		{if !$meta->map['address'] && $settings->addressHideEmptyFields}{else}
		<div class="address-row row-postal-address" itemscope itemtype="http://schema.org/PostalAddress">
			<div class="address-name"><h5>{__ 'Address'}:</h5></div>
			<div class="address-data" itemprop="streetAddress"><p>{if $meta->map['address']}{$meta->map['address']}{else}-{/if}</p></div>
		</div>
		{/if}

		{if !$settings->addressHideGpsField}
		<div class="address-row row-gps" itemscope itemtype="http://schema.org/Place">
			<div class="address-name"><h5>{__ 'GPS'}:</h5></div>
			<div class="address-data" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
				<p>
					{if $meta->map['latitude'] && $meta->map['longitude']}

						{substr($meta->map['latitude'],0,10)}, {substr($meta->map['longitude'],0,10)}
						<meta itemprop="latitude" content="{$meta->map['latitude']}">
						<meta itemprop="longitude" content="{$meta->map['longitude']}">
					{else}-{/if}
				</p>
			</div>
		</div>
		{/if}

		{if !$meta->telephone && $settings->addressHideEmptyFields}{else}
		<div class="address-row row-telephone">
			<div class="address-name"><h5>{__ 'Telephone'}:</h5></div>
			<div class="address-data">
				<p>
					{if $meta->telephone}
						<span itemprop="telephone"><a href="tel:{!str_replace(" ", "", $meta->telephone)}" class="phone">{$meta->telephone}</a></span>
					{else}-{/if}
				</p>

				{if is_array($meta->telephoneAdditional) && count($meta->telephoneAdditional) > 0}
					{foreach $meta->telephoneAdditional as $data}
					<p>
						<span itemprop="telephone"><a href="tel:{$data['number']}" class="phone">{$data['number']}</a></span>
					</p>
					{/foreach}
				{/if}
			</div>

		</div>
		{/if}

		{if !$meta->email && $settings->addressHideEmptyFields}{else}
		<div class="address-row row-email">
			<div class="address-name"><h5>{__ 'Email'}:</h5></div>
			<div class="address-data"><p>{if $meta->email && $meta->showEmail}<a href="mailto:{$meta->email}" target="_top" itemprop="email">{$meta->email}</a>{else}-{/if}</p></div>
		</div>
		{/if}

		{if !$meta->web && $settings->addressHideEmptyFields}{else}
		<div class="address-row row-web">
			<div class="address-name"><h5>{__ 'Web'}:</h5></div>
			<div class="address-data"><p>{if $meta->web}<a href="{$meta->web}" target="_blank" itemprop="url" {if $settings->addressWebNofollow}rel="nofollow"{/if}>{if $meta->webLinkLabel}{$meta->webLinkLabel}{else}{$meta->web}{/if}</a>{else}-{/if}</p></div>
		</div>
		{/if}
	</div>
</div>
