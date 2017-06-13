{capture name=path}
    {l s='PagoEfectivo' mod='pagoefectivo'}
{/capture}

<h1 class="page-heading">
	{l s='Resumen del pedido' mod='pagoefectivo'}
</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nb_products <= 0}
    <p class="alert alert-warning">
        {l s='Your shopping cart is empty.' mod='pagoefectivo'}
    </p>
{else}
    <form action="{$link->getModuleLink('pagoefectivo', 'validation', [], true)|escape:'html'}" method="post">
	<div class="box cheque-box">
		<h3 class="page-subheading">
            {l s='PagoEfectivo' mod='pagoefectivo'}
		</h3>
		<p>
			<ul class="form-list">
			    <li>
			    	<div class="pe-descripcion">
			            <div>
			                <img src="{$home_url}modules/pagoefectivo/img/pagoefectivo.png" alt="PagoEfectivo" height="42px">
			            </div>
			            <div>
			                <div>Es un medio de pago alternativo a las tarjetas de crédito/débito para tus compras por internet.</div>
			                <a onclick="popup('{$pe_url}','PagoEfectivo');" href="javascript:void(0);">{$pe_pregunta}</a>
			            </div>
			        </div>
			        <div class="pe-opcion">
			            <p>Por favor, selecione una opción:</p>
			            <select class="select" required onchange="showDiv(this)">
			                <option value="">Seleccione</option>
			                <option value="1">Transferencia bancaria</option>
			                <option value="2">Depósito bancario</option>
			            </select>
			        </div>
			        <div id="pe-opcion-1" style="display:none">
			            <div class="pe-opcion-descripcion">{$pe_internet}</div>
			            <div class="pe-opcion-img">
			                <img src="{$home_url}modules/pagoefectivo/img/bbva.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/bcp.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/interbank.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/scotiabank.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/banbif.png" height="30px">
			            </div>
			        </div>
					<div id="pe-opcion-2" style="display:none">
			            <div class="pe-opcion-descripcion">{$pe_agencias}</div>
			            <div class="pe-opcion-img">
			                <img src="{$home_url}modules/pagoefectivo/img/bbva.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/bcp.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/interbank.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/scotiabank.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/banbif.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/kasnet.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/western_union.png" height="30px">
			                <img src="{$home_url}modules/pagoefectivo/img/fullcarga.png" height="30px">
			            </div>
			        </div>
			    </li>
			</ul>
		</p>
		<p class="cheque-indent">
			<strong class="dark">
                {l s='Aquí tiene un resumen de su pedido:' mod='pagoefectivo'}
			</strong>
		</p>
		<p>
			- {l s='El importe total de su pedido es' mod='pagoefectivo'}
			<span id="amount" class="price">{displayPrice price=$total_amount}</span>
            {if $use_taxes == 1}
                {l s='(impuestos inc.)' mod='pagoefectivo'}
            {/if}
		</p>
		<p>
			- {l s='La información para realizar el pago a través de PagoEfectivo aparecerá en la página siguiente. ' mod='pagoefectivo'}
			<br />
			- {l s='Por favor, confirme su pedido haciendo clic en "Confirmo mi pedido".' mod='pagoefectivo'}
		</p>
	</div>

	<p class="cart_navigation clearfix" id="cart_navigation">
		<a class="button-exclusive btn btn-default" href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">
			<i class="icon-chevron-left"></i>{l s='Otros métodos de pago' mod='pagoefectivo'}
		</a>
		<button class="button btn btn-default button-medium" type="submit">
			<span>{l s='Confirmo mi pedido' mod='pagoefectivo'}<i class="icon-chevron-right right"></i></span>
		</button>
	</p>
    </form>
{/if}
<style type="text/css">
	.pe-descripcion {
	display: inline-block;
	margin-bottom: 15px;
	}
	.pe-descripcion > div {
		display: inline-block;
		vertical-align: middle;
	}
	.pe-descripcion > div > img {
		margin-right: 10px;
	}
	.pe-opcion {
	    margin-bottom: 10px;
	}
	.pe-opcion-descripcion {
		margin-bottom: 10px;
	}
	.pe-opcion-img img {
		display: inline-block;
		margin: 0 5px 5px 0;
	}
	.pe-opcion label {
	    cursor: pointer;
	    display: inline-block;
	}
	.pe-opcion img {
	    margin-right: 5px;
	    vertical-align: middle;
	}
</style>
<script type="text/javascript">
function popup(url,target) {
    var posicion_x; 
    var posicion_y;
    posicion_x=(screen.width/2)-(600/2); 
    posicion_y=(screen.height/2)-(493/2); 
    window.open(url,target, "width=600,height=493,menubar=0,toolbar=0,directories=0,scrollbars=no,resizable=no,left="+posicion_x+",top="+posicion_y+"");
}
function showDiv(elem){
    if(elem.value == 1) {
        document.getElementById('pe-opcion-1').style.display = "block";
        document.getElementById('pe-opcion-2').style.display = "none";
	}
    if(elem.value == 2) {
        document.getElementById('pe-opcion-1').style.display = "none";
        document.getElementById('pe-opcion-2').style.display = "block";
    }
    if(elem.value == '') {
        document.getElementById('pe-opcion-1').style.display = "none";
        document.getElementById('pe-opcion-2').style.display = "none";
    }
}
</script>