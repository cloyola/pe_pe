{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<div class="row pagoefectivo-header">
		<img src="{$module_dir|escape:'html':'UTF-8'}views/img/template_2_logo.png" class="col-xs-6 col-md-3 text-center" id="payment-logo" />
		<div class="col-xs-6 col-md-6 text-center text-muted">
			{l s='My Payment Module and PrestaShop have partnered to provide the easiest way for you to accurately calculate and file sales tax.' mod='pagoefectivo'}
		</div>
		<div class="col-xs-12 col-md-3 text-center">
			<a href="http://centraldeayuda.pagoefectivo.pe/hc/es/requests/new" onclick="return false;" class="btn btn-primary" id="create-account-btn">{l s='Register your store' mod='pagoefectivo'}</a><br />
			{l s='Already have one?' mod='pagoefectivo'}<a href="https://wallet.pagoefectivo.pe/login" onclick="return false;"> {l s='Log in' mod='pagoefectivo'}</a>
		</div>
	</div>

	<hr />
	
	<div class="pagoefectivo-content">
		<div class="row">
			<div class="col-md-5">
				<h5>{l s='Benefits of using my payment module' mod='pagoefectivo'}</h5>
				<ul class="ul-spaced">
					<li>
						<strong>{l s='Accessible' mod='pagoefectivo'}:</strong>
						{l s='Offer more payment alternatives and channels to your potential customers.' mod='pagoefectivo'}
					</li>
					
					<li>
						<strong>{l s='Safier' mod='pagoefectivo'}:</strong>
						{l s='A single CIP code for each transaction and we have SSL security certificate.' mod='pagoefectivo'}
					</li>
					
					<li>
						<strong>{l s='Flexible' mod='pagoefectivo'}:</strong>
						{l s='Set the expiration time of the CIP according to the needs of your business.' mod='pagoefectivo'}
					</li>
					
					<li>
						<strong>{l s='Multi platform' mod='pagoefectivo'}:</strong>
						{l s='There are no setup fees or long-term contracts. You only pay a low transaction fee.' mod='pagoefectivo'}
					</li>
				</ul>
			</div>
			
			<div class="col-md-2">
				<h5>{l s='Pricing' mod='pagoefectivo'}</h5>
				<dl class="list-unstyled">
					<dt>{l s='Generate CIP code' mod='pagoefectivo'}</dt>
					<dd>{l s='automatically with this module.' mod='pagoefectivo'}</dd>
					<dt>{l s='Provide CIP code' mod='pagoefectivo'}</dt>
					<dd>{l s='instantly to your client' mod='pagoefectivo'}</dd>
					<dt>{l s='Client pay anywhere' mod='pagoefectivo'}</dt>
					<dd>{l s='with the CIP code' mod='pagoefectivo'}</dd>
				</dl>
				<a href="#" onclick="return false;">(Detailed pricing here)</a>
			</div>
			
			<div class="col-md-5">
				<h5>{l s='How does it work?' mod='pagoefectivo'}</h5>
				<iframe src="https://www.youtube.com/embed/N1NJabZgUKQ" width="335" height="188" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
		</div>

		<hr />
		
		<div class="row">
			<div class="col-md-12">
				<p class="text-muted">{l s='My Payment Module accepts more than 80 localized payment methods around the world' mod='pagoefectivo'}</p>
				
				<div class="row">
					<img src="{$module_dir|escape:'html':'UTF-8'}views/img/template_2_cards.png" class="col-md-3" id="payment-logo" />
					<div class="col-md-9 text-center">
						<h6>{l s='For more information, call 888-888-1234' mod='pagoefectivo'} {l s='or' mod='pagoefectivo'} <a href="mailto:contact@prestashop.com">contact@prestashop.com</a></h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<p class="text-muted">
		<i class="icon icon-info-circle"></i> {l s='In order to create a secure account with My Payment Module, please complete the fields in the settings panel below:' mod='pagoefectivo'}
		{l s='By clicking the "Save" button you are creating secure connection details to your store.' mod='pagoefectivo'}
		{l s='My Payment Module signup only begins when you client on "Activate your account" in the registration panel below.' mod='pagoefectivo'}
		{l s='If you already have an account you can create a new shop within your account.' mod='pagoefectivo'}
	</p>
	<p>
		<a href="#" onclick="return false;"><i class="icon icon-file"></i> Link to the documentation</a>
	</p>
</div>