<?php


if (!defined('MASQUERADE_DIR')) {
  define('MASQUERADE_DIR', dirname(__FILE__));
}

class MasqueradePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'define_acl',
    );
    protected $_filters = array('admin_navigation_main', 'public_navigation_admin_bar');

    public function hookDefineAcl($args)
    {
        // Restrict access to super and admin users.
        $args['acl']->addResource('Masquerade_Index');
    }

    public function filterAdminNavigationMain($nav) {
        $nav[] = array(
            'label' => __('Masquerade'),
            'uri' => url('masquerade'),
            'resource' => 'Masquerade_Index',
            'privilege' => 'index'
        );
        return $nav;
    }

    public function filterPublicNavigationAdminBar($navLinks)
    {
        if (is_allowed('Masquerade_Index', 'edit')) {
            $editLinks = array(
                'redirect' => array(
                    'label'=>'Masquerade',
                    'uri' => admin_url('masquerade'),
                    'target' => '_blank',
                ),
            );

            $navLinks = array_merge($editLinks, $navLinks);
        }


        return $navLinks;
    }
}
