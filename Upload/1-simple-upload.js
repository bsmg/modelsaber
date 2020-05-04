/*
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
LICENSE
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

Copyright 2018 by Code Boxx

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
MORE
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
Please visit https://code-boxx.com/ for more!
 */
window.addEventListener("load", function(){
  // GET THE DROP ZONE
  var uploader = document.getElementById('uploader');
  var form = document.getElementById('uploadForm');
  var input = document.getElementById('file-uploader');

  // STOP THE DEFAULT BROWSER ACTION FROM OPENING THE FILE
  uploader.addEventListener("dragover", function (e) {
    e.preventDefault();
    e.stopPropagation();
  });

  // ADD OUR OWN UPLOAD ACTION
  uploader.addEventListener("drop", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // RUN THROUGH THE DROPPED FILES + AJAX UPLOAD
    var items  = e.dataTransfer.items;      // -- Items
    var files = [];
for (var i = 0; i < items.length; i++) 
{
    // Get the dropped item as a 'webkit entry'.
    var entry = items[i].webkitGetAsEntry();

    if (entry.isFile) 
    {
        // Get the same dropped item, but as a File.
        files.push = items[i].getAsFile();
    }
}

    form['file[]'].value = files;
//    input.file.push(e.dataTransfer.files);
    form.submit();
    for (var i = 0; i < e.dataTransfer.files.length; i++) {
      var xhr = new XMLHttpRequest(),
          data = new FormData();
      data.append('file', e.dataTransfer.files[i]);
      xhr.open('POST', form.action, true);
      xhr.onload = function (e) {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            // OK - Do something
            // console.log(xhr.responseText);
            alert("Upload OK!");
          } else {
            // ERROR - Do something
            // console.error(xhr.statusText);
            alert("Upload error!");
          }
        }
      };
      xhr.onerror = function (e) {
        // ERROR - Do something
        // console.error(xhr.statusText);
        alert("Upload error!");
      };
      xhr.send(data);
    }
  });
});