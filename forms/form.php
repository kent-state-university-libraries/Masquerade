<?php

class Masquerade_IndexForm extends Omeka_Form
{
  private $_element = NULL;

  public function init()
  {
      parent::init();
      $this->setMethod('post');
      $this->setAction(url('masquerade/index/save'));
  }

  public function setElement($element) {
      $this->_element = $element;
  }

  public function create() {
      try {
          $this->_registerElements();
      }
      catch (Exception $e) {
        throw $e;
      }
  }

  private function _registerElements()
  {
      $accounts = array();
      $account = current_user();
      $db = get_db();
      $result = $db->query("SELECT id, name FROM `{$db->prefix}users`
        WHERE id <> ?", $account->id);
      while ($row=$result->fetchObject()) {
        $accounts[$row->id] = $row->name;
      }
      $this->addElement('select', 'account', array(
          'label'        => 'Login As',
          'required' => true,
          'multiOptions' => $accounts,
      ));
      $this->addElement('submit', 'login', array('label' => 'Switch User'));
  }
}
