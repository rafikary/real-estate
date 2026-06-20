import { createRouter, createWebHistory } from 'vue-router';
import DashboardPlaceholder from '../views/DashboardPlaceholder.vue';

// Master Land Bank
import MasterLandBankList from '../views/MasterLandBankList.vue';
import LandBankForm from '../views/LandBankForm.vue';

// Master Proyek
import MasterProjectList from '../views/MasterProjectList.vue';
import MasterProjectForm from '../views/MasterProjectForm.vue';

// Update Sertifikat
import UpdateSertifikatList from '../views/UpdateSertifikatList.vue';
import UpdateSertifikatForm from '../views/UpdateSertifikatForm.vue';

// Siteplan
import SiteplanList from '../views/SiteplanList.vue';
import SiteplanForm from '../views/SiteplanForm.vue';

// Split / Grouping Land
import SplitGroupingList from '../views/SplitGroupingList.vue';
import SplitGroupingForm from '../views/SplitGroupingForm.vue';

// Workflow Placeholder View
import WorkflowPlaceholder from '../views/WorkflowPlaceholder.vue';

const routes = [
  { path: '/', name: 'Dashboard', component: DashboardPlaceholder },

  // ── Master Land Bank ──────────────────────────────────────────────────
  { path: '/master/land-bank',              name: 'MasterLandBankList', component: MasterLandBankList },
  { path: '/master/land-bank/create',       name: 'CreateLandBank',     component: LandBankForm,     props: { mode: 'create' } },
  { path: '/master/land-bank/:id/edit',     name: 'EditLandBank',       component: LandBankForm,     props: r => ({ id: r.params.id, mode: 'edit' }) },

  // ── Master Proyek ─────────────────────────────────────────────────────
  { path: '/master/project',                name: 'MasterProjectList',  component: MasterProjectList },
  { path: '/master/project/create',         name: 'CreateProject',      component: MasterProjectForm, props: { mode: 'create' } },
  { path: '/master/project/:id/edit',       name: 'EditProject',        component: MasterProjectForm, props: r => ({ id: r.params.id, mode: 'edit' }) },

  // ── Update Sertifikat ─────────────────────────────────────────────────
  { path: '/master/update-sertifikat',             name: 'UpdateSertifikatList',  component: UpdateSertifikatList },
  { path: '/master/update-sertifikat/create',      name: 'CreateUpdateSertifikat',component: UpdateSertifikatForm, props: { mode: 'create' } },
  { path: '/master/update-sertifikat/:id/edit',    name: 'EditUpdateSertifikat',  component: UpdateSertifikatForm, props: r => ({ id: r.params.id, mode: 'edit' }) },

  // ── Siteplan ──────────────────────────────────────────────────────────
  { path: '/master/siteplan',               name: 'SiteplanList',   component: SiteplanList },
  { path: '/master/siteplan/create',        name: 'CreateSiteplan', component: SiteplanForm, props: { mode: 'create' } },
  { path: '/master/siteplan/:id/edit',      name: 'EditSiteplan',   component: SiteplanForm, props: r => ({ id: r.params.id, mode: 'edit' }) },

  // ── Split / Grouping Land ─────────────────────────────────────────────
  { path: '/master/split-grouping',              name: 'SplitGroupingList',   component: SplitGroupingList },
  { path: '/master/split-grouping/create',       name: 'CreateSplitGrouping', component: SplitGroupingForm, props: { mode: 'create' } },
  { path: '/master/split-grouping/:id/edit',     name: 'EditSplitGrouping',   component: SplitGroupingForm, props: r => ({ id: r.params.id, mode: 'edit' }) },

  // ── Setup Placeholders ────────────────────────────────────────────────
  { path: '/setup/users',                   name: 'SetupUsers',         component: WorkflowPlaceholder, props: { section: 'users' } },
  { path: '/setup/settings',                name: 'SetupSettings',      component: WorkflowPlaceholder, props: { section: 'settings' } },

  // ── Master Placeholders ───────────────────────────────────────────────
  { path: '/master/cost-project',           name: 'MasterCostProject',  component: WorkflowPlaceholder, props: { section: 'cost-project' } },
  { path: '/master/material',               name: 'MasterMaterial',     component: WorkflowPlaceholder, props: { section: 'material' } },

  // ── Real Estate Placeholders ──────────────────────────────────────────
  { path: '/realestate/rab',                name: 'RealEstateRAB',      component: WorkflowPlaceholder, props: { section: 'rab' } },
  { path: '/realestate/pricing',            name: 'RealEstatePricing',  component: WorkflowPlaceholder, props: { section: 'pricing' } },
  { path: '/realestate/budgeting',          name: 'RealEstateBudgeting',component: WorkflowPlaceholder, props: { section: 'budgeting' } },
  { path: '/realestate/progress',           name: 'RealEstateProgress', component: WorkflowPlaceholder, props: { section: 'progress' } },
  { path: '/realestate/realization',        name: 'RealEstateRealization', component: WorkflowPlaceholder, props: { section: 'realization' } },

  // ── Purchasing Placeholders ───────────────────────────────────────────
  { path: '/purchasing/requisition',        name: 'PurchasingRequisition', component: WorkflowPlaceholder, props: { section: 'requisition' } },
  { path: '/purchasing/rfq',                name: 'PurchasingRFQ',         component: WorkflowPlaceholder, props: { section: 'rfq' } },
  { path: '/purchasing/quotation',          name: 'PurchasingQuotation',   component: WorkflowPlaceholder, props: { section: 'quotation' } },
  { path: '/purchasing/mou',                name: 'PurchasingMOU',         component: WorkflowPlaceholder, props: { section: 'mou' } },
  { path: '/purchasing/po',                 name: 'PurchasingPO',          component: WorkflowPlaceholder, props: { section: 'po' } },
  { path: '/purchasing/receipt',            name: 'PurchasingReceipt',     component: WorkflowPlaceholder, props: { section: 'receipt' } },
  { path: '/purchasing/payment-cert',       name: 'PurchasingPaymentCert', component: WorkflowPlaceholder, props: { section: 'payment-cert' } },
  { path: '/purchasing/handover',           name: 'PurchasingHandover',    component: WorkflowPlaceholder, props: { section: 'handover' } },
  { path: '/purchasing/invoice',            name: 'PurchasingInvoice',     component: WorkflowPlaceholder, props: { section: 'invoice' } },
  { path: '/purchasing/payment',            name: 'PurchasingPayment',     component: WorkflowPlaceholder, props: { section: 'payment' } },

  // Fallback
  { path: '/:pathMatch(.*)*', redirect: '/master/land-bank' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
