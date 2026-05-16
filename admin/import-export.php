<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wrap ufg-docs-page" style="margin: 32px 20px 0 10px;">
	<style>
		.ufg-docs-page * { box-sizing: border-box; }
		html { scroll-behavior: smooth; }
		.ufg-docs-page .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
		.ufg-docs-page .flex { display: flex; }
		.ufg-docs-page .flex-col { flex-direction: column; }
		.ufg-docs-page .items-center { align-items: center; }
		.ufg-docs-page .justify-between { justify-content: space-between; }
		.ufg-docs-page .grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
		.ufg-docs-page .gap-6 { gap: 1.5rem; }
		@media (min-width: 1024px) {
			.ufg-docs-page .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
			.ufg-docs-page .lg\:flex-row { flex-direction: row; }
		}
		.ufg-docs-page .bg-white { background-color: #ffffff; }
		.ufg-docs-page .rounded-2xl { border-radius: 1rem; }
		.ufg-docs-page .rounded-3xl { border-radius: 1.5rem; }
		.ufg-docs-page .shadow-sm { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
		.ufg-docs-page .p-6 { padding: 1.5rem; }
		.ufg-docs-page .p-8 { padding: 2rem; }
		.ufg-docs-page .border { border: 1px solid #e2e8f0; }
		.ufg-docs-page .text-gray-900 { color: #1a202c; }
		.ufg-docs-page .text-gray-600 { color: #4a5568; }
		.ufg-docs-page .text-blue-600 { color: #2563eb; }
		.ufg-docs-page .font-black { font-weight: 900; }
		.ufg-docs-page .font-bold { font-weight: 700; }
		.ufg-docs-page .font-medium { font-weight: 500; }
		.ufg-docs-page .tracking-tight { letter-spacing: -0.025em; }
		.ufg-docs-page .no-underline { text-decoration: none; }
		.ufg-docs-page .space-y-4 > * + * { margin-top: 1rem; }
		.ufg-docs-page .space-y-6 > * + * { margin-top: 1.5rem; }
		.ufg-docs-page .space-x-5 > * + * { margin-left: 1.25rem; }
		
		/* Header Icon */
		.ufg-header-icon {
			width: 56px;
			height: 56px;
			background: #2563eb;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 16px;
			color: white;
			box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
		}

		/* Specific UI elements */
		.ufg-btn-primary {
			background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3); border: none; cursor: pointer; transition: all 0.2s;
		}
		.ufg-btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(37, 99, 235, 0.4); }
		.ufg-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; transform: none; }

		.ufg-ie-gallery-list {
			max-height: 250px;
			overflow-y: auto;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			padding: 12px;
			background: #f8fafc;
			margin-bottom: 24px;
		}
		.ufg-ie-gallery-item, .ufg-ie-select-all {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 10px 14px;
			border-radius: 8px;
			cursor: pointer;
			transition: background 0.15s;
		}
		.ufg-ie-gallery-item:hover { background: #e2e8f0; }
		.ufg-ie-gallery-item code { color: #718096; font-size: 12px; background: transparent; padding: 0; }
		.ufg-ie-select-all { border-bottom: 1px solid #e2e8f0; margin-bottom: 6px; padding-bottom: 14px; font-weight: 700; }
		.ufg-ie-empty { color: #a0aec0; text-align: center; padding: 30px; }
		
		.ufg-ie-upload-area {
			border: 2px dashed #cbd5e0;
			border-radius: 16px;
			padding: 40px 20px;
			text-align: center;
			margin-bottom: 24px;
			transition: all 0.2s;
			cursor: pointer;
			background: #f8fafc;
		}
		.ufg-ie-upload-area.dragover { border-color: #2563eb; background: #ebf8ff; }
		.ufg-ie-upload-area .dashicons { display: block; margin: 0 auto 12px; color: #a0aec0; font-size: 48px; width: 48px; height: 48px; }
		.ufg-ie-filename { color: #2563eb; font-weight: 700; margin-top: 12px; }
		.ufg-ie-import-options { margin-bottom: 24px; padding: 14px 16px; background: #edf2f7; border-radius: 8px; font-weight: 500; font-size: 14px; color: #4a5568; }
		
		.ufg-ie-status {
			margin-top: 32px;
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 1.5rem;
			padding: 2rem;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
		}
		.ufg-ie-progress-bar {
			height: 12px;
			background: #edf2f7;
			border-radius: 6px;
			overflow: hidden;
			margin: 16px 0 24px;
		}
		.ufg-ie-progress-fill {
			height: 100%;
			background: linear-gradient(90deg, #2563eb, #60a5fa);
			border-radius: 6px;
			transition: width 0.3s ease;
		}
		.ufg-ie-log {
			max-height: 250px;
			overflow-y: auto;
			font-family: 'JetBrains Mono', monospace, Consolas;
			font-size: 13px;
			color: #4a5568;
			line-height: 1.8;
			padding: 16px;
			background: #f8fafc;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.ufg-ie-log .success { color: #16a34a; font-weight: 600; }
		.ufg-ie-log .error { color: #dc2626; font-weight: 600; }
		.ufg-ie-log .warning { color: #d97706; }
		.ufg-ie-log .info { color: #2563eb; }

		input[type="checkbox"] { width: 18px; height: 18px; border-radius: 4px; border-color: #cbd5e0; }
		input[type="checkbox"]:checked { background-color: #2563eb; border-color: #2563eb; }
	</style>

	<div class="max-w-7xl">
		<!-- Header -->
		<div class="flex flex-col lg:flex-row lg:items-center justify-between mb-10 flex-wrap gap-6" style="border-bottom: 2px solid #edf2f7; padding-bottom: 32px;">
			<div class="flex items-center space-x-5">
				<div class="ufg-header-icon">
					<span class="dashicons dashicons-update" style="font-size: 32px; width: 32px; height: 32px; transform: rotate(90deg);"></span>
				</div>
				<div>
					<h1 class="font-black text-gray-900 tracking-tight" style="font-size: 42px; margin: 0; line-height: 1;">Import / Export <span style="font-size: 14px; background: #2563eb; color: white; padding: 4px 8px; border-radius: 6px; vertical-align: middle; margin-left: 10px;">v1.1.0</span></h1>
					<p class="text-gray-600 font-medium" style="margin-top: 8px; font-size: 16px;">Transfer your filterable galleries seamlessly between WordPress sites.</p>
				</div>
			</div>
			
			<div class="flex items-center gap-4 flex-wrap">
				<a href="<?php echo admin_url('admin.php?page=filter-gallery'); ?>" class="no-underline ufg-btn-primary" style="background: #1a202c; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
					Back to Dashboard
				</a>
			</div>
		</div>

		<div class="grid lg:grid-cols-2 gap-6">
			<!-- EXPORT SECTION -->
			<div class="bg-white border rounded-3xl p-8 shadow-sm">
				<h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 16px 0;">Export Galleries</h2>
				<p class="text-gray-600 font-medium mb-6">Select galleries to export. The exported JSON file will include all gallery settings, filters, and image URLs.</p>
				
				<div class="ufg-ie-gallery-list" id="ufg-export-gallery-list">
					<?php
					global $wpdb;
					$ufg_gallery_key = "ufg_gallery_";
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
					$ufg_all_galleries = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT option_name FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE %s ORDER BY option_id ASC",
							'%' . $wpdb->esc_like($ufg_gallery_key) . '%'
						)
					);
					if (!empty($ufg_all_galleries)) {
						echo '<label class="ufg-ie-select-all"><input type="checkbox" id="ufg-export-select-all"> Select All</label>';
						foreach ($ufg_all_galleries as $gallery) {
							$option_name = $gallery->option_name;
							$underscore_pos = strrpos($option_name, '_');
							$gallery_id = substr($option_name, ($underscore_pos + 1));
							$details = get_option("ufg_details_" . $gallery_id);
							$gallery_name = isset($details['gallery_name']) ? $details['gallery_name'] : 'Gallery #' . $gallery_id;
							echo '<label class="ufg-ie-gallery-item">';
							echo '<input type="checkbox" name="ufg_export_ids[]" value="' . intval($gallery_id) . '"> ';
							echo '<span>' . esc_html($gallery_name) . ' <code>[ufg id="' . intval($gallery_id) . '"]</code></span>';
							echo '</label>';
						}
					} else {
						echo '<p class="ufg-ie-empty">No galleries found.</p>';
					}
					?>
				</div>
				<button type="button" id="ufg-export-btn" class="ufg-btn-primary" <?php echo empty($ufg_all_galleries) ? 'disabled' : ''; ?>>
					<span class="dashicons dashicons-download" style="margin-right:8px; line-height: 1.3;"></span> Export Selected
				</button>
			</div>

			<!-- IMPORT SECTION -->
			<div class="bg-white border rounded-3xl p-8 shadow-sm">
				<h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 16px 0;">Import Galleries</h2>
				<p class="text-gray-600 font-medium mb-6">Upload a previously exported JSON file to import galleries. Images will be downloaded automatically.</p>
				
				<div class="ufg-ie-upload-area" id="ufg-import-drop-zone">
					<div>
						<span class="dashicons dashicons-media-default"></span>
						<p class="font-bold text-gray-900" style="font-size: 16px; margin: 10px 0 6px;">Drag & drop your .json file here</p>
						<p class="text-gray-600 font-medium" style="margin-bottom: 16px;">or click to browse</p>
						<label class="ufg-btn-primary" style="background:#cbd5e0; color:#1a202c; box-shadow:none; padding:8px 16px; font-size:12px;" for="ufg-import-file">Choose File</label>
						<input type="file" id="ufg-import-file" accept=".json" style="display:none;">
						<p class="ufg-ie-filename" id="ufg-import-filename"></p>
					</div>
				</div>
				
				<div class="ufg-ie-import-options" id="ufg-import-options" style="display:none;">
					<label class="flex items-center cursor-pointer gap-2">
						<input type="checkbox" id="ufg-import-skip-images" value="1">
						Skip image download (only import settings & filters)
					</label>
				</div>
				
				<button type="button" id="ufg-import-btn" class="ufg-btn-primary" disabled>
					<span class="dashicons dashicons-upload" style="margin-right:8px; line-height: 1.3;"></span> Import Galleries
				</button>
			</div>
		</div>

		<!-- STATUS / LOG -->
		<div class="ufg-ie-status" id="ufg-ie-status" style="display:none;">
			<h3 class="font-black text-gray-900" id="ufg-ie-status-title" style="margin:0 0 8px; font-size: 20px;">Processing...</h3>
			<div class="ufg-ie-progress-bar">
				<div class="ufg-ie-progress-fill" id="ufg-ie-progress-fill" style="width:0%"></div>
			</div>
			<div class="ufg-ie-log" id="ufg-ie-log"></div>
		</div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {

	// Select All toggle
	$('#ufg-export-select-all').on('change', function() {
		$('input[name="ufg_export_ids[]"]').prop('checked', $(this).is(':checked'));
	});

	// EXPORT
	$('#ufg-export-btn').on('click', function() {
		var ids = [];
		$('input[name="ufg_export_ids[]"]:checked').each(function() {
			ids.push($(this).val());
		});
		if (ids.length === 0) {
			alert('Please select at least one gallery to export.');
			return;
		}
		var $btn = $(this);
		$btn.prop('disabled', true).html('<span class="dashicons dashicons-update-alt" style="margin-right:8px; line-height: 1.3;"></span> Exporting...');
		showStatus('Exporting ' + ids.length + ' gallery(ies)...');
		addLog('Starting export for gallery IDs: ' + ids.join(', '), 'info');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ufg_export_galleries',
				nonce: '<?php echo wp_create_nonce("ufg-import-export"); ?>',
				gallery_ids: ids
			},
			success: function(response) {
				if (response.success) {
					// Trigger download
					var blob = new Blob([JSON.stringify(response.data, null, 2)], {type: 'application/json'});
					var url = URL.createObjectURL(blob);
					var a = document.createElement('a');
					var date = new Date().toISOString().slice(0,10);
					a.href = url;
					a.download = 'filter-gallery-export-' + date + '.json';
					document.body.appendChild(a);
					a.click();
					document.body.removeChild(a);
					URL.revokeObjectURL(url);
					addLog('Export completed! ' + response.data.galleries.length + ' gallery(ies) exported.', 'success');
					setProgress(100);
				} else {
					addLog('Export failed: ' + (response.data || 'Unknown error'), 'error');
				}
				$btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="margin-right:8px; line-height: 1.3;"></span> Export Selected');
			},
			error: function(xhr, status, err) {
				addLog('Export request failed: ' + err, 'error');
				$btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="margin-right:8px; line-height: 1.3;"></span> Export Selected');
			}
		});
	});

	// IMPORT — File handling
	var importFile = null;

	$('#ufg-import-drop-zone').on('click', function(e) {
		if(e.target.tagName.toLowerCase() !== 'label' && e.target.tagName.toLowerCase() !== 'input') {
			$('#ufg-import-file').click();
		}
	});

	$('#ufg-import-file').on('change', function(e) {
		if (e.target.files.length) {
			setImportFile(e.target.files[0]);
		}
	});

	$('#ufg-import-drop-zone').on('dragover', function(e) {
		e.preventDefault();
		$(this).addClass('dragover');
	}).on('dragleave drop', function(e) {
		e.preventDefault();
		$(this).removeClass('dragover');
		if (e.type === 'drop' && e.originalEvent.dataTransfer.files.length) {
			setImportFile(e.originalEvent.dataTransfer.files[0]);
		}
	});

	function setImportFile(file) {
		if (!file.name.endsWith('.json')) {
			alert('Please select a valid .json file.');
			return;
		}
		importFile = file;
		$('#ufg-import-filename').text('Selected: ' + file.name + ' (' + formatBytes(file.size) + ')');
		$('#ufg-import-btn').prop('disabled', false);
		$('#ufg-import-options').slideDown(200);
	}

	function formatBytes(bytes) {
		if (bytes < 1024) return bytes + ' B';
		if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
		return (bytes / 1048576).toFixed(1) + ' MB';
	}

	// IMPORT — Process
	$('#ufg-import-btn').on('click', function() {
		if (!importFile) return;
		var $btn = $(this);
		$btn.prop('disabled', true).html('<span class="dashicons dashicons-update-alt" style="margin-right:8px; line-height: 1.3;"></span> Reading file...');
		showStatus('Importing galleries...');

		var reader = new FileReader();
		reader.onload = function(e) {
			try {
				var data = JSON.parse(e.target.result);
			} catch (err) {
				addLog('Invalid JSON file: ' + err.message, 'error');
				$btn.prop('disabled', false).html('<span class="dashicons dashicons-upload" style="margin-right:8px; line-height: 1.3;"></span> Import Galleries');
				return;
			}

			if (!data.plugin || data.plugin !== 'filter-gallery-pro' || !data.galleries) {
				addLog('This file is not a valid Filter Gallery Pro export.', 'error');
				$btn.prop('disabled', false).html('<span class="dashicons dashicons-upload" style="margin-right:8px; line-height: 1.3;"></span> Import Galleries');
				return;
			}

			addLog('Found ' + data.galleries.length + ' gallery(ies) in export file (v' + data.version + ' from ' + data.source_url + ')', 'info');
			$btn.html('<span class="dashicons dashicons-update-alt" style="margin-right:8px; line-height: 1.3;"></span> Importing...');

			var skipImages = $('#ufg-import-skip-images').is(':checked') ? 1 : 0;

			// Import galleries one by one
			importNext(data.galleries, 0, skipImages, $btn);
		};
		reader.readAsText(importFile);
	});

	function importNext(galleries, idx, skipImages, $btn) {
		if (idx >= galleries.length) {
			addLog('All ' + galleries.length + ' gallery(ies) imported successfully!', 'success');
			setProgress(100);
			$btn.prop('disabled', false).html('<span class="dashicons dashicons-upload" style="margin-right:8px; line-height: 1.3;"></span> Import Galleries');
			return;
		}

		var g = galleries[idx];
		var pct = Math.round(((idx) / galleries.length) * 100);
		setProgress(pct);
		var gName = (g.details && g.details.gallery_name) ? g.details.gallery_name : 'Gallery #' + g.original_id;
		addLog('Importing "' + gName + '" (' + (idx + 1) + '/' + galleries.length + ')...', 'info');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ufg_import_gallery',
				nonce: '<?php echo wp_create_nonce("ufg-import-export"); ?>',
				gallery_data: JSON.stringify(g),
				skip_images: skipImages
			},
			timeout: 120000,
			success: function(response) {
				if (response.success) {
					addLog('✓ "' + gName + '" imported as gallery #' + response.data.new_id +
						(response.data.images_imported > 0 ? ' (' + response.data.images_imported + ' images)' : "") +
						(response.data.images_failed > 0 ? ' (' + response.data.images_failed + ' images failed)' : ""),
						response.data.images_failed > 0 ? 'warning' : 'success'
					);
				} else {
					addLog('✗ Failed to import "' + gName + '": ' + (response.data || 'Unknown error'), 'error');
				}
				importNext(galleries, idx + 1, skipImages, $btn);
			},
			error: function(xhr, status, err) {
				addLog('✗ Request failed for "' + gName + '": ' + err, 'error');
				importNext(galleries, idx + 1, skipImages, $btn);
			}
		});
	}

	function showStatus(title) {
		$('#ufg-ie-status').slideDown(300);
		$('#ufg-ie-status-title').text(title);
		$('#ufg-ie-log').empty();
		setProgress(0);
	}
	function addLog(msg, cls) {
		$('#ufg-ie-log').append('<div class="' + (cls || '') + '">' + msg + '</div>');
		$('#ufg-ie-log').scrollTop($('#ufg-ie-log')[0].scrollHeight);
	}
	function setProgress(pct) {
		$('#ufg-ie-progress-fill').css('width', pct + '%');
	}
});
</script>
