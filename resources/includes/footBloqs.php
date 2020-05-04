<div id="footBloq">
  <div id="footerLine"><hr></div>
  <footer class="footer">
    <div class="content has-text-centered">
      <p><strong><?= SITECAMEL ?></strong> &COPY; <span id="copyrightYear"></span> <a href="https://discord.gg/beatsabermods">BSMG</a></p>
      <span><?= FOOTBLOQ ?></span>
    </div>
  </footer>
  <script>
    document.getElementById("copyrightYear").innerHTML = new Date().getFullYear();
  </script>
</div>