document.addEventListener('click', (e) => {
  if(e.target.matches('.add-btn')) {
    const pid = e.target.dataset.id;
    
    fetch("add_to_cart.php", {
      method: "POST",
      headers: { "Content-Type" : "application/json" },
      body: JSON.stringify({id: pid})
    })
    .then(response => response.text())
    .then(text => {
      console.log(text);
    });
  }
})

