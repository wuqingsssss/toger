<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * Returns a value from the $_SESSION superglobal array corresponding to the 
 * given key.
 * 
 * Options:
 *  [0] $_SESSION key value
 * 
 * @package log4php
 * @subpackage pattern
 * @version $Revision: 1326626 $ 
 * @since 2.3
 */
class LoggerPatternConverterGLOBALS extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
      if(isset($this->option) && $this->option !== ''){
		   if(isset($GLOBALS[$this->option]))
			  return $GLOBALS[$this->option];
		   else
			  return '';
	}
	}
	
}
