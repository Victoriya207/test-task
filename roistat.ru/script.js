let timer;
window.onload=() => 
{
    timer = setTimeout(() => {
        document.getElementById('timeSpent').value = 1; // пользователь провел 30 секунд
    }, 30000); // 30 секунд
};
window.onbeforeunload = () => {
    clearTimeout(timer); // отключаем таймер при уходе со страницы
};