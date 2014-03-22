Name: <?php echo $data['name'] ?><br />
Email: <?php echo $data['email'] ?><br />
Submitted: <?php echo $this->Admin->time($data['created']) ?><br />
Message: <p><?php echo $data['message'] ?></p>