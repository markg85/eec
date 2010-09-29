{if $subPath == "" && ( $item == "overview" || $item == "")}
<h1>Welcome on the <i>{$aModuleInfo.modulename}</i> overview page.</h1>
The following modules are installed: <br />
{foreach $aInstalledModules d}
    
    {$d.modulename} - 
    
    {if $d.enabled}
        <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/enable"}">Disable</a> - 
    {else}
        <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/enable"}">Enable REST</a> - 
    {/if}

    {if $d.restenabled}
        <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/disablerest"}">Disable REST</a> - 
    {else}
        <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/enablerest"}">Enable REST</a> - 
    {/if}

    <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/permissions"}">Manage permissions</a> - 
    
    <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/uninstall"}">uninstall</a>
    
    
    
    
    <br />

{/foreach}
<hr />

The following modules can be installed: <br />
{foreach $aInstallableModules d}
    
    {$d.module} - 
    <a href="{eec_rest_url "/Admin/modules/{$d.module}/install"}">install</a>
    <br />

{/foreach}
{/if}