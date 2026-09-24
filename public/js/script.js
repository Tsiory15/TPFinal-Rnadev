document.addEventListener('DOMContentLoaded',()=>{
    const modal = document.getElementById('exampleModal2')
    const title = document.querySelector('#title2')
    const descriptions = document.querySelector('#description2')
    const price = document.querySelector('#price2')
    const check = document.querySelectorAll('.check')
    const methods = document.querySelector('.hide')
    const imageInput = document.querySelector('#formImage2')
    const idContainer = document.querySelector('.id')
    modal.addEventListener('hidden.bs.modal',()=>{
        check.forEach(e=>{
                e.checked = false
            })
    })
    modal.addEventListener('show.bs.modal',(e) => {
        const button = e.relatedTarget;
        const titre = button.getAttribute('data-bs-titre')
        const description = button.getAttribute('data-bs-description')
        const prix = button.getAttribute('data-bs-prix')
        const categ = JSON.parse(button.getAttribute('data-bs-categ'))
        const method = button.getAttribute('data-bs-method')
        const image = button.getAttribute('data-bs-image')
        const name = button.getAttribute('data-bs-name')
        const id = button.getAttribute('data-bs-id')
        
        async function autofill() {
            const response = await fetch(image);
            const blob = await response.blob();

            const file = new File([blob],name,{type:blob.type})
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file)

            imageInput.files = dataTransfer.files
        }
        if(image){
            autofill();
        }else{
            imageInput.value = ''
        }

        title.value = titre;
        descriptions.value = description
        price.value = prix
        methods.value = method
        idContainer.value = id
        
        if(categ){
            categ.forEach(ele => {
                check.forEach(e => {
                   if(e.value == parseInt(ele)){
                        e.checked = true
                   }
                })
            })
        }
    })
})