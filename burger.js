function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
      x.style.display = "none";
    } else {
      x.style.display = "block";
    }
  }

function failNotification(message) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = 'red';
    notification.style.padding = '10px';
    notification.style.borderRadius = '5px';
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000); // Remove after 3 seconds
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = 'lightgreen';
    notification.style.padding = '10px';
    notification.style.borderRadius = '5px';
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000); // Remove after 3 seconds
}

function loadPost(id) {
  location.href = 'blog_detail.php?id=' + id;
}