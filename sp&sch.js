// Schedule Tabs
function showDay(dayId) {
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-button');

    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(button => button.classList.remove('active'));

    document.getElementById(dayId).classList.add('active');
    document.querySelector(`[onclick="showDay('${dayId}')"]`).classList.add('active');
}

// Placeholder for Speaker Details
function showSpeakerDetails(speakerId) {
    alert(`Showing details for speaker: ${speakerId}`);
}
