<?php
/*
 * The MIT License
 *
 * Copyright 2021 sjnx.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>

<div class="w3-card kdt">
    <div class="w3-padding w3-row datatitle">
        <div class="w3-col s12 m3"><a href="{baseurl}/blog/new"><button class="newbtn btng">Add New Blog</button></a></div>
        <div class="w3-col s12 m9 w3-hide-small"><b>Blogs</b></div>

    </div>
    <div class="w3-padding w3-row">
        <div class="w3-col s3">
            <select class="datacounterSelect" onchange="loadurl('{baseurl}/blog/setblogdisplayrow/?blogdisplayrow=' + this.value, 'tabledata');">
                <?php
                foreach ([2, 5, 10, 15, 20, 30, 50, 100, 200, 500] as $value) {
                    if ($value == $_SESSION['blogdisplayrow']) {
                        echo "<option value=\"{$value}\" selected=\"selected\">{$value}</option>";
                    } else {
                        echo "<option value=\"{$value}\">{$value}</option>";
                    }
                }
                ?>

            </select>
        </div>
        <div class="w3-col s9" style="text-align: right;">
            <input type="search" id="searchblog" placeholder="Search your data here...." 
                   onchange="loadurl('{baseurl}/blog/blogdata/?keyw=' + this.value, 'tabledata');"
                   onkeyup="this.onchange();" 
                   onpaste="this.onchange();" 
                   oncut="this.onchange();" 
                   oninput="this.onchange();" >
        </div>
    </div>
    <div id="tabledata">
        <?php
        include('blogdata.php');
        ?>
    </div>
</div>