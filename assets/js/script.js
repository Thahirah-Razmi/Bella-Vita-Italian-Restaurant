const sections = document.querySelectorAll("section");
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  },
  { threshold: 0.5 }
);

sections.forEach((section) => observer.observe(section));

function openForm(formId) {
  document.getElementById(formId).style.display = "block";
}

function closeForm(formId) {
  document.getElementById(formId).style.display = "none";
}

document.getElementById("addUserButton").addEventListener("click", function() {
  openForm("addUserForm");
});

function editUser(id, name, email, username, password, phone, role) {
  document.getElementById('editUserId').value = id;
  document.getElementById('editName').value = name;
  document.getElementById('editEmail').value = email;
  document.getElementById('editUsername').value = username;
  document.getElementById('editPassword').value = password;
  document.getElementById('editPhone').value = phone;
  document.getElementById('editRole').value = role;

  openForm("editUserForm");
}

document.getElementById("addUser").addEventListener("submit", function(event) {
  event.preventDefault(); 

  let formData = new FormData();
  formData.append("add_user", "1");
  formData.append("name", document.getElementById("name").value);
  formData.append("email", document.getElementById("email").value);
  formData.append("username", document.getElementById("username").value);
  formData.append("password", document.getElementById("password").value);
  formData.append("phone", document.getElementById("phone").value);
  formData.append("role", document.getElementById("role").value);

  fetch("Manage Users.php", {
      method: "POST",
      body: formData
  })
  .then(response => response.text())
  .then(data => {
      alert(data);
      location.reload();
  })
  .catch(error => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
  });
});

document.getElementById("editUser").addEventListener("submit", function(event) {
  event.preventDefault(); 

  let formData = new FormData();
  formData.append("edit_user", "1");
  formData.append("user_id", document.getElementById("editUserId").value);
  formData.append("name", document.getElementById("editName").value);
  formData.append("email", document.getElementById("editEmail").value);
  formData.append("username", document.getElementById("editUsername").value);
  formData.append("password", document.getElementById("password").value);
  formData.append("phone", document.getElementById("editPhone").value);
  formData.append("role", document.getElementById("editRole").value);

  fetch("Manage Users.php", {
      method: "POST",
      body: formData
  })
  .then(response => response.text())
  .then(data => {
      alert(data);
      location.reload();
  })
  .catch(error => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
  });
});

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
      fetch("Manage Users.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "delete_user=1&user_id=" + userId
      })
      .then(response => response.text())
      .then(data => {
          alert(data);
          location.reload();
      })
      .catch(error => {
          console.error("Error:", error);
          alert("An error occurred. Please try again.");
      });
  }
}

document.getElementById("addMenuButton").addEventListener("click", function() {
  openForm("addMenuForm");
});

function editMenu(menu_id, dish_name, description, category, price, availability, image_path) {
  document.getElementById('editMenuId').value = menu_id;
  document.getElementById('editDishName').value = dish_name;
  document.getElementById('editDescription').value = description;
  document.getElementById('editCategory').value = category;
  document.getElementById('editPrice').value = price;
  document.getElementById('editAvailability').value = availability;
  document.getElementById('editImage').src = image_path;

  openForm("editMenuForm");
}

document.getElementById("addMenuForm").addEventListener("submit", function(event) {
  event.preventDefault();

  let formData = new FormData();
  formData.append("add_menu", "1");
  formData.append("dish_name", document.getElementById("dishName").value);
  formData.append("description", document.getElementById("description").value);
  formData.append("category", document.getElementById("category").value);
  formData.append("price", document.getElementById("price").value);
  formData.append("availability", document.getElementById("availability").value);
  formData.append("image", document.getElementById("image").files[0]);

  fetch("Manage Menu.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    location.reload();
  })
  .catch(error => {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  });
});

document.getElementById("editMenuForm").addEventListener("submit", function(event) {
  event.preventDefault();

  let formData = new FormData();
  formData.append("edit_menu", "1");
  formData.append("menu_id", document.getElementById("editMenuId").value);
  formData.append("dish_name", document.getElementById("editDishName").value);
  formData.append("description", document.getElementById("editDescription").value);
  formData.append("category", document.getElementById("editCategory").value);
  formData.append("price", document.getElementById("editPrice").value);
  formData.append("availability", document.getElementById("editAvailability").value);
  formData.append("image", document.getElementById("editImage").files[0]);

  fetch("Manage Menu.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    location.reload();
  })
  .catch(error => {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  });
});

function deleteMenu(menuId) {
  if (confirm("Are you sure you want to remove this menu item?")) {
    fetch("Manage Menu.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "delete_menu=1&menu_id=" + menuId
    })
    .then(response => response.text())
    .then(data => {
      alert(data);
      location.reload();
    })
    .catch(error => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
  }
}

function closeForm(formId) {
  document.getElementById(formId).style.display = 'none';
}

document.getElementById('editReservation').addEventListener('submit', function(event) {
  event.preventDefault();

  let formData = new FormData(this);

  fetch('Manage Reservations.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          alert(data); 
          closeForm('editReservationForm'); 
          location.reload(); 
      })
      .catch(error => {
          console.error('Error:', error);
          alert('There was an error updating your reservation.');
      });
});

document.addEventListener("DOMContentLoaded", () => {
  const config = {
    animationThreshold: 0.2,
    headerScrollThreshold: 100,
    cardPerspective: 1000,
    cardRotation: 8,
    apiEndpoints: {
      users: "Manage Users.php",
      menu: "Manage Menu.php",
    },
  };

  const dom = {
    header: document.querySelector("header"),
    forms: {
      addUser: document.getElementById("addUserForm"),
      editUser: document.getElementById("editUserForm"),
      addMenu: document.getElementById("addMenuForm"),
      editMenu: document.getElementById("editMenuForm"),
    },
    interactiveCards: document.querySelectorAll(
      ".menu-item, .table-card, .room-card, .reservation-card"
    ),
  };

  const initScrollAnimations = () => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          entry.target.style.opacity = entry.isIntersecting ? "1" : "0";
          entry.target.style.transform = entry.isIntersecting
            ? "translateY(0)"
            : "translateY(30px)";
        });
      },
      { threshold: config.animationThreshold }
    );

    document
      .querySelectorAll(".animate-on-scroll")
      .forEach((el) => observer.observe(el));
  };

  const handleHeaderScroll = () => {
    window.addEventListener("scroll", () => {
      dom.header.classList.toggle(
        "scrolled",
        window.scrollY > config.headerScrollThreshold
      );
    });
  };

  const initCardInteractions = () => {
    dom.interactiveCards.forEach((card) => {
      card.addEventListener("mousemove", (e) => {
        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;

        card.style.transform = `
                  perspective(${config.cardPerspective}px)
                  rotateX(${y * config.cardRotation}deg)
                  rotateY(${x * config.cardRotation}deg)
                  translateZ(20px)
              `;
      });

      card.addEventListener("mouseleave", () => {
        card.style.transform =
          "perspective(1000px) rotateX(0) rotateY(0) translateZ(0)";
      });
    });
  };

  const handleFormSubmission = async (formId, endpoint, successMessage) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      try {
        const response = await fetch(endpoint, {
          method: "POST",
          body: formData,
        });

        if (!response.ok) throw new Error("Network response was not ok");

        const data = await response.text();
        showNotification(data.includes("success") ? "success" : "error", data);
        setTimeout(() => location.reload(), 1500);
      } catch (error) {
        console.error("Error:", error);
        showNotification("error", "An error occurred. Please try again.");
      }
    });
  };

  const initFormHandlers = () => {
    handleFormSubmission(
      "addUser",
      config.apiEndpoints.users,
      "User added successfully"
    );
    handleFormSubmission(
      "editUser",
      config.apiEndpoints.users,
      "User updated successfully"
    );

    handleFormSubmission(
      "addMenuForm",
      config.apiEndpoints.menu,
      "Menu item added successfully"
    );
    handleFormSubmission(
      "editMenuForm",
      config.apiEndpoints.menu,
      "Menu item updated successfully"
    );

    document.querySelectorAll("[data-form-toggle]").forEach((button) => {
      button.addEventListener("click", () => {
        const formId = button.dataset.formToggle;
        toggleFormVisibility(formId);
      });
    });
  };

  const handleDelete = async (endpoint, id, confirmationMessage) => {
    if (!confirm(confirmationMessage)) return;

    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_${endpoint.split("/").pop()}=1&id=${id}`,
      });

      const data = await response.text();
      showNotification(data.includes("success") ? "success" : "error", data);
      setTimeout(() => location.reload(), 1500);
    } catch (error) {
      console.error("Error:", error);
      showNotification("error", "An error occurred. Please try again.");
    }
  };

  const populateEditForm = (formId, data) => {
    const form = document.getElementById(formId);
    Object.entries(data).forEach(([key, value]) => {
      const input = form.querySelector(
        `[name="edit${key.charAt(0).toUpperCase() + key.slice(1)}"]`
      );
      if (input) input.value = value;
    });
    toggleFormVisibility(formId);
  };

  const toggleFormVisibility = (formId) => {
    const form = document.getElementById(formId);
    if (form)
      form.style.display = form.style.display === "none" ? "block" : "none";
  };

  const showNotification = (type, message) => {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.remove(), 3000);
  };

  initScrollAnimations();
  handleHeaderScroll();
  initCardInteractions();
  initFormHandlers();

  document.body.addEventListener("click", (e) => {
    if (e.target.matches(".cancel-btn")) {
      handleDelete(
        config.apiEndpoints.users,
        e.target.dataset.userId,
        "Are you sure you want to cancel this reservation?"
      );
    }
  });
});

function toggleMenu() {
  document.querySelector(".nav-links").classList.toggle("show");
}

