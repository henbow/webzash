<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		return;
	}
	
	function index()
	{
		$this->template->set('page_title', 'Administer Webzash');

		$data['current_account'] = "";

		/* Checking for valid database connection */
		if ($this->db->conn_id)
		{
			/* Checking for valid database name, username, password */
			if ($this->db->query("SHOW TABLES"))
			{
				$valid_webzash_db = TRUE;
				/* Check for valid webzash database */
				$table_names = array('settings', 'groups', 'ledgers', 'vouchers', 'voucher_items');
				foreach ($table_names as $id => $tbname)
				{
					$valid_db_q = mysql_query('DESC ' . $tbname);
					if ( ! $valid_db_q)
					{
						$valid_webzash_db = FALSE;
						$this->messages->add('Invalid Webzash database', 'error');
						break;
					}
				}

				/* Loading account data */
				if ($valid_webzash_db)
				{
					$valid_db_q = mysql_query('DESC settings');
					if ($valid_db_q)
					{
						$account_q = $this->db->query('SELECT * FROM settings WHERE id = 1');
						if ($account_d = $account_q->row())
						{
							$data['current_account'] .= "Currently active account is ";
							$data['current_account'] .= "<b>" . $account_d->name . "</b>";
							$data['current_account'] .= " from " . "<b>" . date_mysql_to_php($account_d->ay_start) . "</b>";
							$data['current_account'] .= " to " . "<b>" . date_mysql_to_php($account_d->ay_end) . "</b>";
							$data['current_account'] .= " ( " . anchor('admin/active', 'change active account', array('title' => 'Activate a existing account', 'style' => 'color:#000000')) . " )";
						}
					}
				}
			}
		}

		if ($data['current_account'] == "")
			$data['current_account'] = "No account is currently active. You can " . anchor('admin/create', 'create', array('title' => 'Create a new account', 'style' => 'color:#000000')) . " a new account or " . anchor('admin/active', 'activate', array('title' => 'Activate a existing account', 'style' => 'color:#000000')) . " an existing account";

		$this->template->load('admin_template', 'admin/welcome', $data);
		return;
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/admin/welcome.php */