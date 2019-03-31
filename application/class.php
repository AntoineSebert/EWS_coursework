<?
class user extends html_content {
	/* ATTRIBUTES */
		private $email = '';
		private $password_hash = '';
		private $salt = '';
		/*
		private $old_feed = array();
		private $new_feed = array();
		*/
		private $last_poll = 0;

	/* MEMBERS */
		function __construct(string $_email, string $_password_hash, string $_salt) {
			$this->email = $_email;
			$this->password_hash = $_password_hash;
			$this->salt = $_salt;
			$this->last_poll = time();
		}
		public function get_email() : string { return $this->email; }
		/*
		public function get_new_feed() : string {
			return $this->new_feed;
		}
		public function get_new_feed_publication(int $index) : string {
			$value = new stdClass();
			try {
				$value = $this->new_feed[$index];
			}
			catch(OutOfBoundsException $e) {
				echo($e);
				echo(count($this->new_feed)." < ".$index);
			}
			finally {
				return $value;
			}
		}
		public function get_old_feed() : string {
			return $this->old_feed;
		}
		public function get_old_feed_publication(int $index) : string {
			$value = new stdClass();
			try {
				$value = $this->old_feed[$index];
			}
			catch(OutOfBoundsException $e) {
				echo($e);
				echo(count($this->new_feed)." < ".$index);
			}
			finally {
				return $value;
			}
		}
		*/
}

class feed implements ArrayAccess, Iterator, JsonSerializable {
	/* ATTRIBUTES */
		private $protocol;
		private $url = "";
		private $publications = array();
		const PROTOCOLS = array(
			Pair("RSS", "2.0"),
			Pair("Atom", "1.0"),
		);
	/* MEMBERS */
		// custom
			function __construct(string $_url, Pair $_protocol) {
				$this->url = $_url;
				$this->protocol = $_protocol;
			}
			public function get_protocol() : Pair { return $this->protocol; }
			public function get_url() : string { return $this->url; }
		// ArrayAccess
			public function offsetExists(mixed $offset) : bool { return isset($this->publications[$offset]); }
			public function offsetGet(mixed $offset) : mixed {
				return isset($this->publications[$offset]) ? $this->publications[$offset] : null;
			}
			public function offsetSet(mixed $offset, mixed $value) : void {
				if (is_null($offset)) {
					$this->publications[] = $value;
				} else {
					$this->publications[$offset] = $value;
				}
			}
			public function offsetUnset(mixed $offset) : void { unset($this->publications[$offset]); }
		// Iterator
			public function current() : mixed {
				var_dump(__METHOD__);
				return $this->publications[$this->position];
			}
			public function key() : scalar {
				var_dump(__METHOD__);
				return $this->position;
			}
			public function next() : void {
				var_dump(__METHOD__);
				++$this->position;
			}
			public function rewind() : void {
				var_dump(__METHOD__);
				$this->position = 0;
			}
			public function valid() : bool {
				var_dump(__METHOD__);
				return isset($this->publications[$this->position]);
			}
		// JsonSerializable
			public function jsonSerialize() : mixed { return Pair(array([$url, $protocol]), $this->publications); }
}

class publication implements JsonSerializable {
	/* ATTRIBUTES */
	/* MEMBERS */
		// custom
			public function as_html() : string {}
		// JsonSerializable
			public function jsonSerialize() : mixed {
				return "";
			}
}
