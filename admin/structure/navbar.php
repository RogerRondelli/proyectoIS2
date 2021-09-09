<input id="imgcfg" type="hidden">
<div id="scrollTop"></div>
<div class="navbar navbar-fixed-top" role="navigation" id="accionar" >
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse">
	   <?php echo menu($_SESSION['sfpy_usuario'],1); ?>
    </div>
  </div>
</div>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			event.preventDefault();
			event.stopPropagation();
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
	});

})(jQuery);
//cambio de local
 $('#change_sucursal').click(()=>{
   $('#changeLocal').modal('show')
 })

 const aceptar_cambio = () =>{
   let id_local = $('#change_select_local').val();
   let id_empresa = $('#change_select_local option:selected').attr('data-val');
   $.post('inc/dashboard.php',{q:'cambio_local',id_local:id_local,id_empresa:id_empresa},function(d){
     location.reload()
   })
 }

// fin cambio local

</script>
<style media="screen">
    .fil_empresa{
      color:DarkRed;
    }
    .fil_t_locales{
      color:OrangeRed;
    }
    .fil_local{
      /* color:Green; */
    }
    .fil_sublocal{
      color:Green;
    }
    .fil_t_sublocal{
      color:DarkOrange;
    }
</style>