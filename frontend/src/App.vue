<template>
  <div class="app-layout">
    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">

      <!-- Brand -->
      <div class="sidebar-brand">
        <div class="brand-logo-box">
          <span class="brand-icon-text">RE</span>
        </div>
        <div class="brand-text-group">
          <span class="brand-name">REAL ESTATE</span>
          <span class="brand-sub">MANAGEMENT</span>
        </div>
        <button class="brand-chevron-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
      </div>

      <!-- Search -->
      <div class="sidebar-search">
        <div class="search-box">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" v-model="searchQuery" placeholder="Search menu..." />
        </div>
        <button class="refresh-btn">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          Refresh Menu
        </button>
      </div>

      <!-- Nav -->
      <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>

        <!-- NOTIFIKASI -->
        <div class="nav-item" style="cursor: not-allowed; opacity: 0.6;">
          <span class="nav-ico">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </span>
          <span class="nav-label">NOTIFIKASI</span>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-item" style="cursor: not-allowed; opacity: 0.6;">
          <span class="nav-ico">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          </span>
          <span class="nav-label">DASHBOARD</span>
        </div>

        <!-- SETUP -->
        <div class="nav-group" :class="{ 'nav-group--open': setupOpen }">
          <div class="nav-item nav-item--group-header" style="cursor: not-allowed; opacity: 0.6;">
            <span class="nav-ico">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
            </span>
            <span class="nav-label">SETUP</span>
            <span class="nav-arrow nav-arrow--chevron" :class="{ 'rotated': setupOpen }">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
          </div>
          <div class="nav-subitems" v-show="setupOpen">
            <router-link to="/setup/users" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
              <span>USER MANAGEMENT</span>
            </router-link>
            <router-link to="/setup/settings" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
              <span>APP SETTINGS</span>
            </router-link>
          </div>
        </div>

        <!-- PURCHASING -->
        <div class="nav-group" :class="{ 'nav-group--open': purchasingOpen }">
          <div class="nav-item nav-item--group-header" style="cursor: not-allowed; opacity: 0.6;">
            <span class="nav-ico">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </span>
            <span class="nav-label">PURCHASING</span>
            <span class="nav-arrow nav-arrow--chevron" :class="{ 'rotated': purchasingOpen }">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
          </div>
          <div class="nav-subitems" v-show="purchasingOpen">
            <router-link to="/purchasing/requisition" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></span>
              <span>PR (PERMINTAAN)</span>
            </router-link>
            <router-link to="/purchasing/rfq" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
              <span>RFQ</span>
            </router-link>
            <router-link to="/purchasing/quotation" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
              <span>QUOTATION SUPPLIER</span>
            </router-link>
            <router-link to="/purchasing/mou" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
              <span>MOU KONTRAK</span>
            </router-link>
            <router-link to="/purchasing/po" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>
              <span>PURCHASE ORDER</span>
            </router-link>
            <router-link to="/purchasing/receipt" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></span>
              <span>TERIMA MATERIAL</span>
            </router-link>
            <router-link to="/purchasing/payment-cert" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg></span>
              <span>PAYMENT CERTIFICATE</span>
            </router-link>
            <router-link to="/purchasing/handover" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
              <span>BERITA ACARA (BAST)</span>
            </router-link>
            <router-link to="/purchasing/invoice" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span>
              <span>TERIMA TAGIHAN</span>
            </router-link>
            <router-link to="/purchasing/payment" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
              <span>PEMBAYARAN HUTANG</span>
            </router-link>
          </div>
        </div>

        <!-- MARKETING -->
        <div class="nav-item" style="cursor: not-allowed; opacity: 0.6;">
          <span class="nav-ico">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
          </span>
          <span class="nav-label">MARKETING</span>
        </div>

        <!-- INVENTORY -->
        <div class="nav-item" style="cursor: not-allowed; opacity: 0.6;">
          <span class="nav-ico">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
          </span>
          <span class="nav-label">INVENTORY</span>
        </div>

        <!-- REAL ESTATE -->
        <div class="nav-group" :class="{ 'nav-group--open': realEstateOpen }">
          <div class="nav-item nav-item--group-header" style="cursor: not-allowed; opacity: 0.6;">
            <span class="nav-ico">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            <span class="nav-label">REAL ESTATE</span>
            <span class="nav-arrow nav-arrow--chevron" :class="{ 'rotated': realEstateOpen }">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
          </div>
          <div class="nav-subitems" v-show="realEstateOpen">
            <router-link to="/transaction/update-landbank" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></span>
              <span>UPDATE LANDBANK</span>
            </router-link>
            <router-link to="/master/update-sertifikat" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
              <span>UPDATE SERTIFIKAT</span>
            </router-link>
            <router-link to="/master/split-grouping" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg></span>
              <span>SPLIT/GROUPING LAND</span>
            </router-link>
            <router-link to="/realestate/rab" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
              <span>RAB PROYEK</span>
            </router-link>
            <router-link to="/realestate/pricing" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span>
              <span>PENENTUAN HARGA</span>
            </router-link>
            <router-link to="/realestate/budgeting" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span>
              <span>BUDGETING (COA)</span>
            </router-link>
            <router-link to="/realestate/progress" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg></span>
              <span>CHECKLIST PROGRESS</span>
            </router-link>
            <router-link to="/realestate/realization" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></span>
              <span>REALISASI PROYEK</span>
            </router-link>
          </div>
        </div>

        <!-- MASTER GROUP -->
        <div class="nav-group" :class="{ 'nav-group--open': masterOpen }">
          <div class="nav-item nav-item--group-header" @click="masterOpen = !masterOpen">
            <span class="nav-ico">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </span>
            <span class="nav-label">MASTER</span>
            <span class="nav-arrow nav-arrow--chevron" :class="{ 'rotated': masterOpen }">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
          </div>
 
          <div class="nav-subitems" v-show="masterOpen">
            <!-- Land Bank -->
            <router-link to="/master/land-bank" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></span>
              <span>LAND BANK</span>
            </router-link>
 
            <!-- Master Proyek -->
            <router-link to="/master/project" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></span>
              <span>MASTER PROYEK</span>
            </router-link>
 
            <!-- Siteplan -->
            <router-link to="/master/siteplan" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>
              <span>SITEPLAN</span>
            </router-link>

            <!-- Master Interface -->
            <router-link to="/master/interface" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2" ry="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span>
              <span>MASTER INTERFACE</span>
            </router-link>

            <!-- Master Item -->
            <router-link to="/master/item" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>
              <span>MASTER ITEM</span>
            </router-link>

            <!-- Master Supplier -->
            <router-link to="/master/supplier" class="nav-subitem" active-class="nav-subitem--active">
              <span class="nav-sub-ico"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/><path d="M9 7h6"/><path d="M9 11h6"/></svg></span>
              <span>MASTER SUPPLIER</span>
            </router-link>
          </div>
        </div>

        <!-- DEV -->
        <div class="nav-item" style="cursor: not-allowed; opacity: 0.6;">
          <span class="nav-ico">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </span>
          <span class="nav-label">DEV</span>
        </div>

      </nav>
    </aside>

    <!-- ===== MAIN AREA ===== -->
    <div class="main-container">
      <!-- Top Header -->
      <header class="top-header">
        <div class="header-left">
          <div class="breadcrumbs">
            <span class="crumb-parent">{{ breadcrumbParent }}</span>
            <span class="crumb-separator">/</span>
            <span class="crumb-active">{{ currentRouteName }}</span>
          </div>
        </div>

        <div class="header-right">
          <div class="header-datetime">
            <span class="header-day">{{ clockDate }}</span>
            <span class="header-time">{{ clockTime }}</span>
          </div>
          <div class="header-profile">
            <div class="profile-avatar">A</div>
            <div class="profile-info">
              <div class="profile-name">Admin</div>
              <div class="profile-role">Superadmin</div>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="content-body">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const searchQuery = ref('');
const setupOpen = ref(false);
const purchasingOpen = ref(false);
const realEstateOpen = ref(false);
const masterOpen = ref(true); // default open

const breadcrumbParent = computed(() => {
  const name = route.name || '';
  if (name.startsWith('Setup')) return 'SETUP';
  if (name.startsWith('Purchasing')) return 'PURCHASING';
  if (name.startsWith('RealEstate') || name.includes('Sertifikat') || name.includes('SplitGrouping') || name.includes('Landbank')) return 'REAL ESTATE';
  if (name.includes('LandBank') || name.includes('Project') || name.includes('Siteplan') || name.includes('Cost') || name.includes('Material') || name.includes('Interface') || name.includes('Item') || name.includes('Supplier')) return 'MASTER';
  return 'MENU';
});

const currentRouteName = computed(() => {
  switch (route.name) {
    case 'MasterLandBankList':      return 'LAND BANK';
    case 'CreateLandBank':          return 'FORM LAND BANK';
    case 'EditLandBank':            return 'EDIT LAND BANK';
    case 'MasterProjectList':       return 'MASTER PROYEK';
    case 'CreateProject':           return 'FORM PROYEK';
    case 'EditProject':             return 'EDIT PROYEK';
    case 'UpdateSertifikatList':    return 'UPDATE SERTIFIKAT';
    case 'CreateUpdateSertifikat':  return 'FORM UPDATE SERTIFIKAT';
    case 'EditUpdateSertifikat':    return 'EDIT UPDATE SERTIFIKAT';
    case 'SiteplanList':            return 'SITEPLAN';
    case 'CreateSiteplan':          return 'FORM SITEPLAN';
    case 'EditSiteplan':            return 'EDIT SITEPLAN';
    case 'SplitGroupingList':       return 'SPLIT / GROUPING LAND';
    case 'CreateSplitGrouping':     return 'FORM SPLIT / GROUPING';
    case 'EditSplitGrouping':       return 'EDIT SPLIT / GROUPING';
    case 'MasterInterfaceList':     return 'MASTER INTERFACE';
    case 'CreateMasterInterface':   return 'FORM INTERFACE';
    case 'EditMasterInterface':     return 'EDIT INTERFACE';
    case 'MasterItemList':          return 'MASTER ITEM';
    case 'CreateMasterItem':        return 'FORM ITEM';
    case 'EditMasterItem':          return 'EDIT ITEM';
    case 'MasterSupplierList':      return 'MASTER SUPPLIER';
    case 'CreateMasterSupplier':    return 'FORM SUPPLIER';
    case 'EditMasterSupplier':      return 'EDIT SUPPLIER';
    case 'UpdateLandbankList':      return 'UPDATE LANDBANK';
    case 'CreateUpdateLandbank':    return 'FORM UPDATE LANDBANK';
    case 'EditUpdateLandbank':      return 'EDIT UPDATE LANDBANK';

    // Setup
    case 'SetupUsers':              return 'USER MANAGEMENT';
    case 'SetupSettings':           return 'APP SETTINGS';

    // Master Placeholders
    case 'MasterCostProject':       return 'MASTER COST PROJECT';
    case 'MasterMaterial':          return 'MASTER MATERIAL';

    // Real Estate Placeholders
    case 'RealEstateRAB':           return 'RAB PROYEK';
    case 'RealEstatePricing':       return 'PENENTUAN HARGA';
    case 'RealEstateBudgeting':     return 'BUDGETING (COA)';
    case 'RealEstateProgress':      return 'CHECKLIST PROGRESS';
    case 'RealEstateRealization':   return 'REALISASI PROYEK';

    // Purchasing Placeholders
    case 'PurchasingRequisition':   return 'PERMINTAAN PEMBELIAN';
    case 'PurchasingRFQ':           return 'RFQ';
    case 'PurchasingQuotation':     return 'QUOTATION SUPPLIER';
    case 'PurchasingMOU':           return 'MOU KONTRAK';
    case 'PurchasingPO':            return 'PURCHASE ORDER (PO)';
    case 'PurchasingReceipt':       return 'TANDA TERIMA MATERIAL';
    case 'PurchasingPaymentCert':   return 'SERTIFIKAT PEMBAYARAN';
    case 'PurchasingHandover':      return 'BERITA ACARA (BAST)';
    case 'PurchasingInvoice':       return 'TANDA TERIMA TAGIHAN';
    case 'PurchasingPayment':       return 'PEMBAYARAN HUTANG';

    default:                        return 'DASHBOARD';
  }
});

// Live clock
const clockDate = ref('');
const clockTime = ref('');
let clockInterval = null;

const dayNames = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

const updateClock = () => {
  const now = new Date();
  clockDate.value = `${dayNames[now.getDay()]}, ${now.getDate()} ${monthNames[now.getMonth()]} ${now.getFullYear()}`;
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  const s = String(now.getSeconds()).padStart(2,'0');
  const sess = now.getHours() < 12 ? 'Pagi' : now.getHours() < 15 ? 'Siang' : now.getHours() < 18 ? 'Sore' : 'Malam';
  clockTime.value = `${sess}, ${h}:${m}:${s}`;
};

onMounted(() => { updateClock(); clockInterval = setInterval(updateClock, 1000); });
onUnmounted(() => clearInterval(clockInterval));
</script>

<style>
/* Sidebar styles — global because sidebar is in App.vue root */
</style>
