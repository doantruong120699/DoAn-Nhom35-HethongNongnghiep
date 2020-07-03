$.ajaxSetup({cache:false});
// Thiết lập thời gian thực vòng lặp 1 giây
setInterval(function() {$('.main-seendata').load('loadseendata.php');}, 1000);
