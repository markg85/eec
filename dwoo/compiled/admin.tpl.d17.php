<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?>ADMIN TEMPLATE

<hr />
The following modules are installed: <br />
<?php 
$_fh0_data = (isset($this->scope["aData"]) ? $this->scope["aData"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['d'])
	{
/* -- foreach start output */
?>
    
    <?php echo $this->scope["d"]["modulename"];?> - 
    
    <?php if ((isset($this->scope["d"]["enabled"]) ? $this->scope["d"]["enabled"]:null)) {
?>
        <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/disable">Disable</a> - 
    <?php 
}
else {
?>
        <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/enable">Enable REST</a> - 
    <?php 
}?>


    <?php if ((isset($this->scope["d"]["restenabled"]) ? $this->scope["d"]["restenabled"]:null)) {
?>
        <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/disablerest">Disable REST</a> - 
    <?php 
}
else {
?>
        <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/enablerest">Enable REST</a> - 
    <?php 
}?>


    <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/permissions">Manage permissions</a> - 
    
    <a href="/Admin/<?php echo $this->scope["d"]["modulerestname"];?>/remove">uninstall</a>
    
    
    <br />

<?php 
/* -- foreach end output */
	}
}?>

<hr />

The following modules can be installed: <br />
<?php 
$_fh1_data = (isset($this->scope["aUninstalledModules"]) ? $this->scope["aUninstalledModules"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['d'])
	{
/* -- foreach start output */
?>
    
    <?php echo $this->scope["d"]["module"];?> - 
    
    <a href="/Admin/<?php echo $this->scope["d"]["module"];?>/install">install</a>
    
    <br />

<?php 
/* -- foreach end output */
	}
}?>

<hr />

<?php if ((isset($this->scope["page"]) ? $this->scope["page"] : null) == "permissions") {
?>
<table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
    <tr style="font-weight: bold;">
        <td>Role</td>
        <td>Create</td>
        <td>Read</td>
        <td>Update</td>
        <td>delete</td>
    </tr>
    
<?php 
$_fh2_data = (isset($this->scope["aPermissions"]) ? $this->scope["aPermissions"] : null);
if ($this->isArray($_fh2_data) === true)
{
	foreach ($_fh2_data as $this->scope['d'])
	{
/* -- foreach start output */
?>
    <tr>
        <td><?php echo $this->scope["d"]["name"];?></td>
        <td><?php echo $this->scope["d"]["create"];?></td>
        <td><?php echo $this->scope["d"]["read"];?></td>
        <td><?php echo $this->scope["d"]["update"];?></td>
        <td><?php echo $this->scope["d"]["delete"];?></td>
    </tr>
<?php 
/* -- foreach end output */
	}
}?>

</table>
<?php 
}
 /* end template body */
return $this->buffer . ob_get_clean();
?>