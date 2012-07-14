<?php
/**
 * Jaxl (Jabber XMPP Library)
 *
 * Copyright (c) 2009-2012, Abhinav Singh <me@abhinavsingh.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * * Neither the name of Abhinav Singh nor the names of his
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRIC
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once JAXL_CWD.'/core/jaxl_loop.php';

class JAXLCli {
	
	public static $counter = 0;
	private $in = null;
	//private $out = null;
	
	private $recv_cb = null;
	private $recv_chunk_size = 1024;
	
	public function __construct($recv_cb=null) {
		// enable -icanon mode to read char by char
		// dont forget to call system("stty sane"); 
		// to reset back tty
		// system("stty -icanon");
		
		$this->recv_cb = $recv_cb;
		$this->in = fopen('php://stdin', 'r');
		//$this->out = fopen('php://stdout', 'w');
		
		// catch read event on stdin
		JAXLLoop::watch($this->in, array(
			'read' => array(&$this, 'on_read_ready')
		));
		
		// catch write event on stdout
		//JAXLLoop::watch($this->in, array(
			//'write' => array(&$this, 'on_write_ready')
		//));
	}
	
	public function on_read_ready($in) {
		$raw = @fread($in, $this->recv_chunk_size);
		if($this->recv_cb) call_user_func($this->recv_cb, $raw);
		JAXLCli::prompt();
	}
	
	public function on_write_ready($out) {
		
	}
	
	public static function prompt() {
		++self::$counter;
		echo "jaxl ".self::$counter."> ";
	}
	
}

?>
