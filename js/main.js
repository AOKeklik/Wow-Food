 const ROOT = "http://localhost/Wow-Food/"

/* components */
new class NavUpdate {
    constructor () {
        this.__toggle () 
    }
    __toggle () {
        document.addEventListener ("click", e => {
            const node = e.target
            const allActiveParent = document.querySelectorAll (".nav-update.active")
            const parent = node.closest(".nav-update")
            const btn = node.closest(".nav-update-toggle")

            allActiveParent.forEach(el => el.classList.remove ("active")) 

            if (btn)  parent.classList.toggle ("active")
        })
    }
}
const Modal = new class Modal {
    btns = document.querySelectorAll (".modal-btn")
    constructor () {
        this.__toggle () 
    }
    __toggle () {
        document.addEventListener ("click", e => {
            const node = e.target
            const allMadals = document.querySelectorAll (".modal")
            const allactiveMadals = document.querySelectorAll (".modal.active")
            const btn = node.closest(".modal-btn")
            const content = node.closest(".modal-content")
            
            if (btn) {
                const id = btn.getAttribute("data-modal-id")
                allMadals.forEach (el => {
                    if (el.hasAttribute("data-modal-id"))
                        if (el.getAttribute("data-modal-id") === id)
                            el.classList.toggle ("active")
                })
            } else 
                if (!content) 
                    allactiveMadals.forEach(el => el.classList.remove ("active")) 
        })
    }
    close () {
        const allactiveMadals = document.querySelectorAll (".modal.active")
        allactiveMadals.forEach(el => el.classList.remove ("active")) 
    }
}
const Form = new class Form {
    forms = document.querySelectorAll (".form")
    madals = document.querySelectorAll (".modal")
    constructor () {
        this.__animatePlaceholder () 
    }
    __animatePlaceholder () {
        this.forms.forEach (form => {
            const labels = form.querySelectorAll ("label")
            labels.forEach (label => {
                const input = label.querySelector("input") || label.querySelector("textarea")
                input.addEventListener("focus", e => {
                    label.classList.add("active")
                })
                input.addEventListener("blur", e => {
                    if (input.value.trim() === "")
                        label.classList.remove("active")
                })
            })
        })
    }
    clear () {
        this.forms.forEach (form => {
            const labels = form.querySelectorAll ("label")
            labels.forEach (label => {
                const input = label.querySelector("input") || label.querySelector("textarea")
                input.value = ""
            })
        })
    }
}


/* users */
function addUser (e) {
    try {
        e.preventDefault ()

        const node = e.target
        const parent = node.closest(".modal")
        const table = document.querySelector(".table-full table tbody")
        const name = parent.querySelector("input[name='name']").value.trim()
        const userName = parent.querySelector("input[name='username']").value.trim()
        const password = parent.querySelector("input[name='password']").value.trim()
        const confirmpassword = parent.querySelector("input[name='confirmpassword']").value.trim()
        const message = parent.querySelector(".message")
        const formData = new FormData ()

        formData.append("add-user", true)
        formData.append("name", name)
        formData.append("username", userName)
        formData.append("password", password)

        let errors = []

        if (name === "" )
            errors.push("Name field is required!") 
        else if (userName === "")
            errors.push("User Name field is required!") 
        else if (password === "") 
            errors.push("Password field is required!") 
        else if (password !== confirmpassword) 
            errors.push("Passwords do not match!") 

        if (errors.length === 0) {
            errors = []
            message.classList.remove("active")

            $.ajax ({
                url: ROOT.concat("ajax/user.php"),
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (data) {
                    console.log(data)  

                    table.insertAdjacentHTML ("beforeend", data)
                    Modal.close ()
                    Form.clear ()
                },
                error: (jqXHR, textStatus, errorThrow) => {
                    console.log(jqXHR, textStatus, errorThrow)
                }
            })
        } else {
            message.innerHTML = errors[0]
            message.classList.add("active")
        }
    } catch (err) {
        console.log("addUser: " + err)
    }
}
function deleteUser (e) {}
function updateUser (e) {}