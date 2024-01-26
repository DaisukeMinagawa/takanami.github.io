// メニューとハンバーガーメニューの要素を取得
var menu = document.getElementById("nav-list");
var hamburger = document.getElementById("nav-icon");

// メニューが開いているかどうかを追跡するフラグ
var isMenuOpen = false;

// ハンバーガーメニューのクリックイベントリスナーを追加
hamburger.addEventListener("click", function (event) {
  // ビューポートの幅が768px以下の場合のみ、ハンバーガーメニューの動作を有効にする
  if (window.matchMedia("(max-width: 768px)").matches) {
    // メニューの表示状態を切り替える
    if (!isMenuOpen) {
      menu.style.display = "block";
      hamburger.classList.add("open"); // .openクラスを追加
    } else {
      menu.style.display = "none";
      hamburger.classList.remove("open"); // .openクラスを削除
    }

    // メニューの状態を更新
    isMenuOpen = !isMenuOpen;

    // イベントの伝播を止める
    event.stopPropagation();
  }
});

// ページ全体にクリックイベントリスナーを追加
document.addEventListener("click", function (event) {
  // ビューポートの幅が768px以下の場合のみ、ハンバーガーメニューの動作を有効にする
  if (window.matchMedia("(max-width: 768px)").matches) {
    // クリックされた要素がメニュー内にあるかどうかをチェック
    var isClickInside = menu.contains(event.target);

    // クリックされた要素がメニュー外であれば、メニューを閉じる
    if (!isClickInside && isMenuOpen) {
      menu.style.display = "none";
      hamburger.classList.remove("open"); // .openクラスを削除
      isMenuOpen = false; // メニューの状態を更新
    }
  }
});

// セクションのスライド移動
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});
