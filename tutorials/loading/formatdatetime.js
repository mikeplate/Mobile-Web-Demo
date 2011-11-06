function formatDateTime(datetime) {
    function pad(num) { return (num<10 ? "0":"") + num };
    return datetime.getFullYear() + "-" 
        + pad(datetime.getMonth()+1) + "-" 
        + pad(datetime.getDate()) + " "
        + pad(datetime.getHours()) + ":"
        + pad(datetime.getMinutes());
}

