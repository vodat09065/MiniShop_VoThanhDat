document.getElementById("image")?.addEventListener("change", function () {
    const preview = document.getElementById("preview");
    if (!preview) return;
    
    preview.innerHTML = "";
    const files = this.files;
    
    for (let i = 0; i < files.length; i++) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.width = 150;
        img.className = "img-thumbnail m-1";
        preview.appendChild(img);
    }
});

document.getElementById("images")?.addEventListener("change", function () {
    const preview = document.getElementById("preview-gallery");
    if (!preview) return;
    
    preview.innerHTML = "";
    const files = this.files;
    
    for (let i = 0; i < files.length; i++) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.width = 100;
        img.className = "img-thumbnail m-1";
        preview.appendChild(img);
    }
});
