<?php /* Smarty version Smarty-3.1.8, created on 2012-03-23 11:19:15
         compiled from "/Users/tom/Sites/BrewBot/application/Views/Smarty/Templates/Controller/Signin/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10292805704f6c55e8ca1b14-22200470%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad547394d2df601d6e3fc366aab0c1d14e98ac8e' => 
    array (
      0 => '/Users/tom/Sites/BrewBot/application/Views/Smarty/Templates/Controller/Signin/index.tpl',
      1 => 1332501554,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10292805704f6c55e8ca1b14-22200470',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f6c55e8cd6d29_88903353',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f6c55e8cd6d29_88903353')) {function content_4f6c55e8cd6d29_88903353($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	
	<div class="content">
		<h1>Sign in</h1>
		<p><a href="/signin/do/"><img src="/images/sign-in-with-twitter.png" alt="sign in with twitter"></a></p>
	</div>
	<!-- /content -->

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>