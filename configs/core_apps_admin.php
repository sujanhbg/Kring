<?php

/*
 * Copyright (c) 2020, sjnx
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

$core['defaultController'] = "Home";
$core['defaultMethod'] = "index";
$core['defaultVersion'] = "dev-master";
$core['baseurl'] = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://" . $_SERVER['SERVER_NAME'] . "/krcpmain";
$core['siteurl'] = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://" . $_SERVER['SERVER_NAME'];
$core['theme'] = dirname(__DIR__) . "/apps_admin/" . $core['defaultVersion'] . "/views";
$core['accesslimit'] = false;
$core['AllowedCountry'] = ["Bangladesh"];
$core['AllowProxy'] = false;
$core['filepath'] = dirname(__DIR__) . "/public/imgs";
$core['imagethumbspath'] = dirname(__DIR__) . "/public/thumb";
$core['fileurl'] = $core['siteurl'] . "/imgs";
$core['thumburl'] = $core['siteurl'] . "/thumb";
$core['dbBackupFilePath'] = dirname(__DIR__) . "/kdata/dbbackup";
/*
 * True if web want to save visitor log in database
 * it need to more space to database
 * couse this save every visitor request of website
 */
$core['SaveLogInDb'] = false;
$core['loginwithDB'] = true;

