// Inline JavaScript for this page
document.addEventListener('DOMContentLoaded', function() {
    // Add todo button click handler
    document.getElementById('addTodoBtn').addEventListener('click', function() {
       
        // You can implement a modal or form here
    });
    
    // Todo checkbox toggle
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskText = this.closest('tr').querySelector('span');
            if (this.checked) {
                taskText.classList.add('line-through', 'text-text-secondary');
            } else {
                taskText.classList.remove('line-through', 'text-text-secondary');
            }
        });
    });
});

document.querySelectorAll('[data-editable]').forEach(item => {
  const input = item.querySelector('.edit-input')
  const sizer = item.querySelector('.input-sizer')

  const resize = () => {
    sizer.textContent = input.value || ' '
    input.style.width = sizer.offsetWidth + 2 + 'px'
  }

  resize()
  input.addEventListener('input', resize)  
  // double click → edit
  item.addEventListener('dblclick', () => {
    item.classList.add('editing')
    input.focus()
    input.select()
  })

  const save = () => {
    const value = input.value.trim()

    // xoá hết chữ → xoá item
    if (value === '') {
      item.remove()
      return
    }

    input.value = value
    item.classList.remove('editing')
  }

  input.addEventListener('blur', save)

  input.addEventListener('keydown', e => {
    if (e.key === 'Enter') save()
    if (e.key === 'Escape') {
      item.classList.remove('editing')
    }
  })
})

function openTodoModal() {
  document.getElementById("addTodoModal").classList.remove("hidden");
  document.getElementById("todoInput").focus();
}

function closeTodoModal() {
  document.getElementById("addTodoModal").classList.add("hidden");
}

function saveTodo() {
  const value = document.getElementById("todoInput").value.trim();
  if (!value) return;

  alert("New group: " + value); // sau này thay bằng AJAX
  closeTodoModal();
}

async function createTodo() {
    const inputForm = document.getElementById("todoInput")

    const title = inputForm.value.trim();
    if (!title) {
        return showToast("Tên group không được để trống")
    }
    console.log("clicked");
    const res = await fetch ("/study-tools-website/api/todo/createGroup", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:   "action=createGroup&title=" + encodeURIComponent(title)
        
    })

    const data = await res.json();

    if (data.success) {
        renderGroup(data.group);
        input.value = "";
        closeTodoModal();
    } else {
        showToast(data.error || "Error")
    }
}

function renderGroup(group) {
    const ul = document.getElementById("groupList");
    const li = document.createElement("li");
    li.className = "card-list-item";
    li.dataset.id = group.todolist_id;
    li.innerHTML = `
        <li data-id="${group.group_id}" class="card-list-item">
            <span class="label">${group.title}</span>

            <input class="edit-input" type="text" value="${group.title}" hidden />

            <div class="actions">
                <button class="icon-btn" onclick="enableEdit(this)">✏️</button>
                <button class="icon-btn" onclick="deleteItem(this)">🗑</button>
            </div>
        </li>
    `
    ul.appendChild(li);
}