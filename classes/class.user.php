<?php


class User
{

	private $db;

	function __construct($db)
	{
		// parent::__construct();

		$this->db = $db;
	}

	function add_post($postTitle, $postSlug, $image, $postDesc, $postCont, $postTags)
	{
		$stmt = $this->db->prepare('INSERT into blog_posts (postTitle,postSlug,image,postDesc,postCont,postDate,postTags) VALUES (:postTitle,:postSlug,:image,:postDesc,:postCont,:postDate,:postTags');
		$stmt->execute(array(
			":postTitle" => $postTitle,
			":postSlug" => $postSlug,
			":image" => $image,
			":postDesc" => $postDesc,
			":postCont" => $postCont,
			":postDate" => date('Y m d H:i:s'),
			":postTags" => $postTags
		));
	}
	//check login true
	public function is_logged_in()
	{
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			return true;
		}
	}

	//get password user
	private function get_user_hash($username)
	{

		try {
			$stmt = $this->db->prepare('SELECT memberID, username, password FROM blog_members WHERE username = :username');
			$stmt->execute(array(':username' => $username));

			return $stmt->fetch();
		} catch (PDOException $e) {
			echo '<p class="error">' . $e->getMessage() . '</p>';
		}
	}

	//check match user login
	public function login($username, $password)
	{

		$user = $this->get_user_hash($username);
		if ($user) {
			if (password_verify($password, $user['password']) == 1) {

				$_SESSION['loggedin'] = true;
				$_SESSION['memberID'] = $user['memberID'];
				$_SESSION['username'] = $user['username'];
				return true;
			}
		}
	}


	//logout
	public function logout()
	{
		session_destroy();
	}
}
