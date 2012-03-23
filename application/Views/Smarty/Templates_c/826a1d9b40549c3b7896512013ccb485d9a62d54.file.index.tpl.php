<?php /* Smarty version Smarty-3.1.8, created on 2012-03-23 12:33:49
         compiled from "/Users/tom/Sites/BrewBot/application/Views/Smarty/Templates/Controller/Default/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10987974194f6c55e50f01e2-65385003%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '826a1d9b40549c3b7896512013ccb485d9a62d54' => 
    array (
      0 => '/Users/tom/Sites/BrewBot/application/Views/Smarty/Templates/Controller/Default/index.tpl',
      1 => 1332505837,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10987974194f6c55e50f01e2-65385003',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f6c55e5166bb6_03687366',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f6c55e5166bb6_03687366')) {function content_4f6c55e5166bb6_03687366($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	
	<div class="content">
		<h1>Welcome to LSF!</h1>
		<p>This is your index page.</p>
		
		<div class="hr"><hr /></div>
	
		
		<h2>Where does everything live?</h2>
		<ul>
			<li>Templates in: /application/Views/Smarty/Templates</li>
			<li>Publically accessible files (assets, etc) in: /public</li>
		</ul>
	</div>
	<!-- /content -->

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>