<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
    }

    .preview-image {
      width: 200px;
      cursor: pointer;
      display: block;
      margin: 50px auto;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }

    .modal-content {
        background-color: rgba(67,67,67,90%);
        color: whitesmoke;
        margin: 5% auto;
        padding: 20px;
        width: 80%;
        max-width: 1000px;
        max-height: 400px;
        display: flex;
        gap: 20px;
    }

    .modal-image-container {
        align-self: center;
        position: relative;
        flex: 0 0 50%;
    }

    .modal-image {
      width: 100%;
      position: sticky;
      top: 20px;
    }

    .button-74 {
        background-color: #fbeee0;
        border: 2px solid #422800;
        border-radius: 30px;
        box-shadow: #422800 4px 4px 0 0;
        color: #422800;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        font-size: 18px;
        padding: 0 18px;
        line-height: 50px;
        text-align: center;
        text-decoration: none;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-74:hover {
        background-color: #fff;
    }

    .button-74:active {
        box-shadow: #422800 2px 2px 0 0;
        transform: translate(2px, 2px);
    }

    @media (min-width: 768px) {
        .button-74 {
            min-width: 120px;
            padding: 0 25px;
        }
    }

    .details-button {
      display: block;
      margin-top: 15px;
      padding: 10px 15px;
      background-color: #3498db;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .modal-text {
      flex: 1;
      max-height: 80vh;
      overflow-y: auto;
    }

    .close {
      position: absolute;
      right: 20px;
      top: 10px;
      font-weight: bold;
      font-size: 40px;
      color: #ffffff;
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php
  $imagePreview = "Logo-Boutique.png";
  $modalText = <<<TEXT
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis bibendum lectus vel lacus semper auctor. Fusce nec tortor est. Phasellus dignissim lorem id laoreet ultricies.
    Curabitur ac tortor eu arcu gravida pretium eget accumsan mi. Integer semper volutpat dui vitae tincidunt. Vivamus elit ipsum, maximus vel tempor et, laoreet in lorem. Curabitur cursus nulla vel nisi pulvinar laoreet. 
    Sed ut maximus enim. Maecenas vel justo ac elit facilisis eleifend sit amet sit amet dolor. Ut orci neque, iaculis at pharetra in, efficitur vitae massa. Donec quis lobortis ex.
    Vestibulum dignissim, erat nec pellentesque aliquam, eros sem sollicitudin diam, id congue elit tellus a nisl. Sed imperdiet ultricies arcu dignissim dignissim. 
    Phasellus mauris velit, imperdiet et mauris ac, placerat laoreet turpis. Fusce a luctus diam. Aliquam et lacus vitae tortor sodales posuere. Quisque sed tempor urna, sed dignissim quam. Suspendisse ac aliquam diam. 
    Curabitur vestibulum sed lacus vel congue.
  TEXT;
?>

<img src="<?= $imagePreview ?>" alt="Image" class="preview-image" id="openModal" />

<div class="modal" id="imageModal">
  <div class="modal-content">
    <span class="close" id="closeModal">&times;</span>
    <div class="modal-image-container">
      <img src="<?= $imagePreview ?>" alt="Sticky Image" class="modal-image">
    </div>
    <div class="modal-text">
        <p><?= nl2br($modalText) ?></p>
        <button class="button-74" role="button">Détails</button>
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById("imageModal");
  const openBtn = document.getElementById("openModal");
  const closeBtn = document.getElementById("closeModal");

  openBtn.onclick = () => {
    modal.style.display = "block";
  }

  closeBtn.onclick = () => {
    modal.style.display = "none";
  }

  window.onclick = (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  }
</script>

</body>
</html>
