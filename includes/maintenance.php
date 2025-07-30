<style>
	.maintenance {
	    position: fixed;
	    width: 100vw;
	    height:100vh;
	    padding: 20px;
	    background: rgba(0, 0, 0, 0.3);
	    border-radius: 10px;
	    backdrop-filter: blur(3px);
	    -webkit-backdrop-filter: blur(3px);
	    text-align: center;
	    z-index: 1000;
	}

    .update-container {
        max-width: 600px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        
        position:absolute;
        top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
    }
    .update-container h1 {
        font-size: 2em;
        color: #e63946;
        margin:0;
        margin-bottom:20px;
    }
    .update-container p {
        font-size: 1.1em;
    }
    .update-spinner {
        margin: 20px auto;
        width: 50px;
        height: 50px;
        border: 6px solid #ddd;
        border-top: 6px solid #e63946;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div id="loadmaintainancepage">
	<div class="update-container">
	    <i class="fa fa-exclamation-triangle icon-color-orange" style="font-size:72px" aria-hidden="true"></i>
	    <h1>System Maintenance</h1>
	    <p>The application is currently under maintenance.<br>Please check back shortly.</p>
	</div>
</div>


<script>
    setTimeout(() => {
        location.reload();
    }, 600000);
</script>
