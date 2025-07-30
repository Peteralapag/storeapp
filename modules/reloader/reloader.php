<style>

.loader-container {
	position: fixed;
	top: 0;
	left: 0;
	height: 100vh;
	width: 100vw;
	background: rgba(255, 255, 255, 0.8);
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 9999;
	display: none;
}

.loader-inner {
	position: relative;
	width: 100px;
	height: 100px;
}

.loader-logo {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 80px;
	height: 80px;
	transform: translate(-50%, -50%);
	z-index: 2;
}

.spinner {
	width: 100px;
	height: 100px;
	border: 8px solid #ccc;
	border-top: 8px solid #3498db;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	to {
		transform: rotate(360deg);
	}
}


</style>


<div id="psaSpinner" class="loader-container">
	<div class="loader-inner">
		<img src="../../images/rosebakeshop_logo.png" alt="Logo" class="loader-logo">
		<div class="spinner"></div>
	</div>
</div>


<script>

function psaSpinnerOn() {
	
	var loader = document.getElementById("psaSpinner");
	
	if (loader) {
		loader.style.display = "flex";
	} else {
		console.warn("psaSpinner element not found!");
	}
}

function psaSpinnerOff() {
	
	var loader = document.getElementById("psaSpinner");
	
	if (loader) {
		loader.style.display = "none";
	} else {
		console.warn("psaSpinner element not found!");
	}
}


</script>


