  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  // Tastenkombinationen abfangen
  document.addEventListener('keydown', function(e) {
    // F12
    if (e.key === 'F12') {
      e.preventDefault();
      return false;
    }

    // STRG+SHIFT+I/J/C
    if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) {
      e.preventDefault();
      return false;
    }

    // STRG+U (Seitenquelltext)
    if (e.ctrlKey && e.key.toLowerCase() === 'u') {
      e.preventDefault();
      return false;
    }

    // STRG+S (Speichern)
    if (e.ctrlKey && e.key.toLowerCase() === 's') {
      e.preventDefault();
      return false;
    }
  });

  // DevTools-Detection über Dimensionsprüfung
  const detectDevTools = () => {
    const threshold = 160;
    if (window.outerWidth - window.innerWidth > threshold ||
        window.outerHeight - window.innerHeight > threshold) {
      document.body.innerHTML = "<h1 style='color: red; text-align: center;'>Entwicklertools verboten</h1>";
    }
  };

  setInterval(detectDevTools, 1000);
