package net.python.gui.hud;

import net.minecraft.client.Minecraft;
import net.minecraft.client.gui.Gui;
import net.minecraft.client.gui.GuiScreen;
import net.minecraft.client.gui.ScaledResolution;
import net.minecraft.client.renderer.GlStateManager;
import net.minecraft.client.renderer.Tessellator;
import net.minecraft.client.renderer.WorldRenderer;
import net.minecraft.client.renderer.vertex.DefaultVertexFormats;
import net.minecraft.util.ResourceLocation;
import net.python.mods.Category;
import net.python.mods.Mod;
import net.python.mods.ModInstances;
import org.lwjgl.input.Keyboard;
import org.lwjgl.opengl.GL11;

import java.awt.*;
import java.io.IOException;
import java.util.ArrayList;

public class GUIModMenu extends GuiScreen {

    private ArrayList<Mod> hudMods = new ArrayList<Mod>();
    private ArrayList<Mod> hypixelMods = new ArrayList<Mod>();
    private ArrayList<Mod> mechanicMods = new ArrayList<Mod>();
    private ArrayList<Mod> displayMods = new ArrayList<Mod>();
    private ArrayList<Mod> chatMods = new ArrayList<Mod>();
    private ArrayList<Mod> vanillahudMods = new ArrayList<Mod>();

    private static ModMenu activeMenu;
    private ArrayList<ModMenu> modMenus = new ArrayList<ModMenu>();

    private int circleRadius = 120;
    private int circleBackRadius = 30;
    private int circleSettingsRadius = circleBackRadius + 17;

    public GUIModMenu() {
        for(Mod mod : ModInstances.getCreatedMods()) {
            if(mod.getCategory().equals(Category.Hud)){
                hudMods.add(mod);
            } else if(mod.getCategory().equals(Category.Hypixel)){
                hypixelMods.add(mod);
            } else if(mod.getCategory().equals(Category.Mechanic)){
                mechanicMods.add(mod);
            } else if(mod.getCategory().equals(Category.Display)){
                displayMods.add(mod);
            } else if(mod.getCategory().equals(Category.Chat)){
                chatMods.add(mod);
            } else if(mod.getCategory().equals(Category.Vanillahud)){
                vanillahudMods.add(mod);
            }
        }

        initializeModMenus();
    }

    private ModMenu getModMenuByName(String name) {
        for(ModMenu menus : modMenus) {
            if(menus.getName().equalsIgnoreCase(name)) {
                return menus;
            }
        }
        return null;
    }

    private void initializeModMenus(){

        ArrayList<ModMenuButton> chatModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : chatMods){
            chatModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        makeMenu("chatModsMenu", chatModsMenuButtons, "mechanicModsMenu");

        ArrayList<ModMenuButton> vanillahudModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : vanillahudMods){
            vanillahudModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        makeMenu("vanillahudModsMenu", vanillahudModsMenuButtons, "hudModsMenu");

        ArrayList<ModMenuButton> hudModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : hudMods){
            hudModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        hudModsMenuButtons.add(new ModMenuButtonMenu("Vanilla", getModMenuByName("vanillahudModsMenu")));
        makeMenu("hudModsMenu", hudModsMenuButtons, "main");

        ArrayList<ModMenuButton> hypixelModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : hypixelMods){
            hypixelModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        makeMenu("hypixelModsMenu", hypixelModsMenuButtons, "main");

        ArrayList<ModMenuButton> mechanicModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : mechanicMods){
            mechanicModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        mechanicModsMenuButtons.add(new ModMenuButtonMenu("Chat", getModMenuByName("chatModsMenu")));
        makeMenu("mechanicModsMenu", mechanicModsMenuButtons, "main");

        ArrayList<ModMenuButton> displayModsMenuButtons = new ArrayList<ModMenuButton>();
        for(Mod mod : displayMods){
            displayModsMenuButtons.add(new ModMenuButtonMod(mod.getName(), mod));
        }
        makeMenu("displayModsMenu", displayModsMenuButtons, "main");

        ArrayList<ModMenuButton> mainMenuButtons = new ArrayList<ModMenuButton>();
        for(ModMenu menus : modMenus){
            if(menus.getName().equalsIgnoreCase("hudModsMenu")){
                mainMenuButtons.add(new ModMenuButtonMenu("HUD", menus));
            } else if(menus.getName().equalsIgnoreCase("hypixelModsMenu")){
                mainMenuButtons.add(new ModMenuButtonMenu("Hypixel", menus));
            } else if(menus.getName().equalsIgnoreCase("mechanicModsMenu")){
                mainMenuButtons.add(new ModMenuButtonMenu("Mechanic", menus));
            } else if(menus.getName().equalsIgnoreCase("displayModsMenu")){
                mainMenuButtons.add(new ModMenuButtonMenu("Display", menus));
            }
        }
        makeMenu("main", mainMenuButtons, null);
    }

    private void makeMenu(String name, ArrayList<ModMenuButton> buttons, String backMenu) {
        ModMenu mainMenu = new ModMenu(name, buttons, backMenu);
        modMenus.add(mainMenu);
        if(name == "main") {
            setActiveMenu(mainMenu);
        }
    }

    @Override
    public void drawScreen(int mouseX, int mouseY, float partialTicks){

        super.drawDefaultBackground();

        final float zBackup = this.zLevel;

        this.zLevel = 200;

        // DRAW SCREEN
        ScaledResolution sr = new ScaledResolution(Minecraft.getMinecraft());

        GlStateManager.pushMatrix();
        GL11.glColor3f(0f, 0f, 0f);
        drawCircle((float) sr.getScaledWidth()/2f, (float) sr.getScaledHeight()/2f, circleRadius+1, (int)((360)), 360);
        GlStateManager.popMatrix();

        int buttonCount = activeMenu.getButtons().size();

        if(buttonCount == 0){
            GlStateManager.pushMatrix();
            GL11.glColor3f(0.2f, 0.2f, 0.2f);
            drawCircle((float) sr.getScaledWidth()/2f, (float) sr.getScaledHeight()/2f, circleRadius, (int)((360)), 360);
            GlStateManager.popMatrix();
            GL11.glColor3f(1, 1, 1);
            double xT = Math.cos((((Math.PI * 2))-((Math.PI*2)/2)));
            double yT = Math.sin(((Math.PI * 2)-((Math.PI*2)/2)));
            fontRendererObj.drawString("No Buttons",  sr.getScaledWidth()/2 + (int) (((circleRadius*0.6)* xT - circleRadius/4)), sr.getScaledHeight()/2 + (int) ((circleRadius*0.6)* yT - 0.2*(circleRadius/4)), -1);
        } else {
            for(int i = buttonCount; i > 0; i--) {
                GlStateManager.pushMatrix();
                if(activeMenu.getButtons().get(i-1).getType() == ModMenuButtonType.Mod){
                    ModMenuButtonMod button = (ModMenuButtonMod) activeMenu.getButtons().get(i-1);
                    if(button.getMod().isEnabled()){
                        GL11.glColor3f(0.4f, 0.8f, 0.4f);
                    } else {
                        GL11.glColor3f(0.8f, 0.4f, 0.4f);
                    }
                } else if(activeMenu.getButtons().get(i-1).getType() == ModMenuButtonType.Menu){
                    GL11.glColor3f(0.5f, 0.5f, 0.8f);
                } else {
                    GL11.glColor3f(0.5f, 0.8f, 0.5f);
                }
                drawCircle((float) sr.getScaledWidth()/2f, (float) sr.getScaledHeight()/2f, circleRadius, (i) * (360/buttonCount), 360);
                GlStateManager.popMatrix();

            }
            double v = ((Math.PI * 2) / buttonCount) - (((Math.PI * 2) / buttonCount) / 4);
            for(int i = buttonCount; i > 0; i--) {
                String text = getActiveMenu().getButtons().get(i-1).getTitle();
                GL11.glColor3f(1, 1, 1);

                double a = ((Math.PI * 2) / buttonCount) * i - (((Math.PI * 2) / buttonCount) / 2);

                if(buttonCount == 1) {
                    a = ((Math.PI * 2) / buttonCount) * i - v;
                }

                fontRendererObj.drawString(text, (int) (sr.getScaledWidth()/2 + ((int) (((circleRadius*0.7)* Math.cos(a) - circleRadius/4)))), sr.getScaledHeight()/2 + -1*((int)((circleRadius*0.6)* Math.sin(a) - 0.2*(circleRadius/4))), -1);
            }
            for(int i = buttonCount; i > 0; i--) {

                if(buttonCount > 1) {
                    int angle = i * (360 / buttonCount);
                    double x = sr.getScaledWidth_double() / 2;
                    double y = sr.getScaledHeight_double() / 2;

                    double x2 = sr.getScaledWidth_double() / 2;
                    double y2 = sr.getScaledHeight_double() / 2;

                    // INNER CIRCLE

                    int newInnerCircleRadius = getActiveMenu().getName().equalsIgnoreCase("main") ? 0 : circleBackRadius;

                    if(angle > 0 && angle <= 90) {

                        x = x + Math.cos(Math.toRadians(angle)) * newInnerCircleRadius;
                        y = y - Math.sin(Math.toRadians(angle)) * newInnerCircleRadius;
                        //System.out.print("Angle original: " + angle + ", Angle 1: " + tempAngle);
                    }

                    else if(angle > 90 && angle <= 180){
                        int tempAngle = -1*(180-angle);
                        x = x + -1*(Math.cos(Math.toRadians(tempAngle)) * newInnerCircleRadius);
                        y = y - -1*(Math.sin(Math.toRadians(tempAngle)) * newInnerCircleRadius);
                        //System.out.println(", Angle 2: " + tempAngle);
                    }
                    else if(angle > 180 && angle <= 270){
                        int tempAngle = (angle-180);
                        x = x + -1*(Math.cos(Math.toRadians(tempAngle)) * newInnerCircleRadius);
                        y = y - -1*(Math.sin(Math.toRadians(tempAngle)) * newInnerCircleRadius);
                    }
                    else if(angle > 270 && angle <= 360){
                        int tempAngle = 360 - angle;
                        x = x + Math.cos(Math.toRadians(angle)) * newInnerCircleRadius;
                        y = y - -1* Math.sin(Math.toRadians(angle)) * newInnerCircleRadius;
                    }

                    // OUTER CIRCLE

                    int newCircleRadius = (getActiveMenu().getName().equalsIgnoreCase("main")) ? circleRadius : circleRadius - circleBackRadius;

                    if(angle > 0 && angle <= 90) {

                        x2 = x + Math.cos(Math.toRadians(angle)) * newCircleRadius;
                        y2 = y - Math.sin(Math.toRadians(angle)) * newCircleRadius;
                        //System.out.print("Angle original: " + angle + ", Angle 1: " + tempAngle);
                    }

                    else if(angle > 90 && angle <= 180){
                        int tempAngle = -1*(180-angle);
                        x2 = x + -1*(Math.cos(Math.toRadians(tempAngle)) * newCircleRadius);
                        y2 = y - -1*(Math.sin(Math.toRadians(tempAngle)) * newCircleRadius);
                        //System.out.println(", Angle 2: " + tempAngle);
                    }
                    else if(angle > 180 && angle <= 270){
                        int tempAngle = (angle-180);
                        x2 = x + -1*(Math.cos(Math.toRadians(tempAngle)) * newCircleRadius);
                        y2 = y - -1*(Math.sin(Math.toRadians(tempAngle)) * newCircleRadius);
                    }
                    else if(angle > 270 && angle <= 360){
                        int tempAngle = 360 - angle;
                        x2 = x + Math.cos(Math.toRadians(angle)) * newCircleRadius;
                        y2 = y - -1*(Math.sin(Math.toRadians(angle)) * newCircleRadius);
                    }/**/

                    GlStateManager.pushMatrix();
                    //GL11.glEnable(GL11.GL_BLEND);
                    GL11.glDisable(GL11.GL_TEXTURE_2D);
                    //GL11.glBlendFunc(GL11.GL_SRC_ALPHA, GL11.GL_ONE_MINUS_SRC_ALPHA);
                    GL11.glColor3f(0, 0, 0);
                    GL11.glLineWidth(3);
                    GL11.glBegin(GL11.GL_LINES);
                    GL11.glVertex3d(x, y, 200);
                    GL11.glVertex3d(x2, y2, 200);
                    GL11.glEnd();
                    GL11.glEnable(GL11.GL_TEXTURE_2D);
                    //GL11.glDisable(GL11.GL_BLEND);
                    GlStateManager.popMatrix();
                }
            }

            for(int i = buttonCount; i > 0; i--) {
                GlStateManager.pushMatrix();
                if(activeMenu.getButtons().get(i-1).getType() == ModMenuButtonType.Mod){
                    ModMenuButtonMod button = (ModMenuButtonMod) activeMenu.getButtons().get(i-1);
                    if(button.getMod().isEnabled()){
                        GL11.glColor3f(0.4f, 0.8f, 0.4f);
                    } else {
                        GL11.glColor3f(0.8f, 0.4f, 0.4f);
                    }
                } else if(activeMenu.getButtons().get(i-1).getType() == ModMenuButtonType.Menu){
                    GL11.glColor3f(0.5f, 0.5f, 0.8f);
                } else {
                    GL11.glColor3f(0.5f, 0.8f, 0.5f);
                }
                float colorR, colorG, colorB;
                boolean isModSettings = false;
                if(activeMenu.getButtons().get(i-1).getType() == ModMenuButtonType.Mod){
                    ModMenuButtonMod tempButton = (ModMenuButtonMod) activeMenu.getButtons().get(i - 1);
                    if(!tempButton.getMod().getModSettings().isEnabled()) {
                        if (tempButton.getMod().isEnabled()) {
                            colorR = 0.4f;
                            colorG = 0.8f;
                            colorB = 0.4f;
                        } else {
                            colorR = 0.8f;
                            colorG = 0.4f;
                            colorB = 0.4f;
                        }
                    } else {
                        isModSettings = true;
                        colorR = 0.5f;
                        colorG = 0.5f;
                        colorB = 0.5f;
                    }
                } else {
                    colorR = 0.5f;
                    colorG = 0.5f;
                    colorB = 0.8f;
                }
                if(isModSettings){
                    GL11.glColor3f(0f, 0f, 0f);
                } else {
                    GL11.glColor3f(colorR, colorG, colorB);
                }
                drawCircle((float) sr.getScaledWidth()/2f, (float) sr.getScaledHeight()/2f, circleSettingsRadius + 1, (i) * (360/buttonCount), 360);
                GL11.glColor3f(colorR, colorG, colorB);
                drawCircle((float) sr.getScaledWidth()/2f, (float) sr.getScaledHeight()/2f, circleSettingsRadius, (i) * (360/buttonCount), 360);
                GlStateManager.popMatrix();

                if(isModSettings) {
                    double a = ((Math.PI * 2) / buttonCount) * i - (((Math.PI * 2) / buttonCount) / 2);

                    if(buttonCount == 1) {
                        a = ((Math.PI * 2) / buttonCount) * i - v;
                    }
                    int circleRadiusSettingsIcon = circleSettingsRadius;
                    int textureWidth = 10;
                    int textureHeight = 10;
                    int x = (int) (sr.getScaledWidth()/2 + circleRadiusSettingsIcon*0.8 * Math.cos(a)) - textureWidth/2;
                    int y = (int) (sr.getScaledHeight()/2 - circleRadiusSettingsIcon*0.8 * Math.sin(a)-9) + textureHeight/2;
                    this.mc.getTextureManager().bindTexture(new ResourceLocation("python/modsettings.png"));
                    Gui.drawModalRectWithCustomSizedTexture(x, y, 0.0F, 0.0F, textureWidth, textureHeight, textureWidth, textureHeight);
                    //fontRendererObj.drawString(text, (int) (sr.getScaledWidth()/2 + ((int) (((circleRadius*0.7)* Math.cos(a) - circleRadius/4)))), sr.getScaledHeight()/2 + -1*((int)((circleRadius*0.6)* Math.sin(a) - 0.2*(circleRadius/4))), -1);

                }

            }

        }
        if(!getActiveMenu().getName().equalsIgnoreCase("main")){
            GL11.glColor3f(0f,0f, 0f);
            drawCircle(sr.getScaledWidth()/2, sr.getScaledHeight()/2, 31, 360, 50);
            GL11.glColor3f(0.7f,0.7f, 0.7f);
            drawCircle(sr.getScaledWidth()/2, sr.getScaledHeight()/2, 30, 360, 50);
            fontRendererObj.drawString("Back", sr.getScaledWidth()/2 - fontRendererObj.getStringWidth("Back")/2, sr.getScaledHeight()/2 - fontRendererObj.FONT_HEIGHT/2, new Color(0, 0, 0, 255).getRGB());
        }

        this.zLevel = zBackup;
    }

    public static final double TWICE_PI = Math.PI*2;
    private static final Tessellator tessellator = Tessellator.getInstance();
    private static final WorldRenderer worldRenderer = tessellator.getWorldRenderer();

    public static void drawCircle(double x, double y, int radius, double degrees, int sides) {
        GL11.glEnable(GL11.GL_BLEND);
        GL11.glDisable(GL11.GL_TEXTURE_2D);
        GL11.glBlendFunc(GL11.GL_SRC_ALPHA, GL11.GL_ONE_MINUS_SRC_ALPHA);

        worldRenderer.begin(GL11.GL_TRIANGLE_FAN, DefaultVertexFormats.POSITION);
        worldRenderer.pos(x, y, 0).endVertex();

        for(int i = 0; i <= sides ;i++) {
            double angle = ((degrees / 360) * TWICE_PI * i / sides);
            //double angle = Math.toRadians(degrees);
            worldRenderer.pos(x + Math.cos(angle) * radius, y - Math.sin(angle) * radius, 0).endVertex();
        }
        tessellator.draw();

        GL11.glEnable(GL11.GL_TEXTURE_2D);
        GL11.glDisable(GL11.GL_BLEND);
    }

    @Override
    protected void keyTyped(char typedChar, int keyCode) throws IOException {
        if(keyCode == Keyboard.KEY_ESCAPE){
            this.modMenus.clear();
            this.hudMods.clear();
            this.hypixelMods.clear();
            this.mechanicMods.clear();
            this.displayMods.clear();
            activeMenu = null;
            this.mc.displayGuiScreen(null);
        }
    }

    @Override
    protected void mouseClicked(int x, int y, int button) throws IOException {
        loadMouseOver(x, y);
    }

    private void loadMouseOver(int x, int y) {
        ScaledResolution sr = new ScaledResolution(Minecraft.getMinecraft());
        int scaledX = x - (sr.getScaledWidth()/2);
        int scaledY = y - (sr.getScaledHeight()/2);
        scaledY*=-1;
        double pyth = Math.sqrt((scaledX * scaledX) + (scaledY * scaledY));
        if(pyth <= circleRadius){
            int newX = scaledX;
            int newY = scaledY;
            if(scaledX == sr.getScaledWidth() / 2){
                newX++;
            } else if(scaledY == sr.getScaledHeight() / 2){
                newY++;
            }
            int buttonCount = getActiveMenu().getButtons().size();
            double centeredX = newX;
            double centeredY = newY;
            if(pyth <= circleBackRadius && !getActiveMenu().getName().equalsIgnoreCase("main")) {
                if(getModMenuByName(activeMenu.getBackMenu()) != null){
                    setActiveMenu(getModMenuByName(activeMenu.getBackMenu()));
                } else {
                    this.modMenus.clear();
                    this.hudMods.clear();
                    this.hypixelMods.clear();
                    this.mechanicMods.clear();
                    this.displayMods.clear();
                    activeMenu = null;
                    this.mc.displayGuiScreen(null);
                }
                return;
            }

            double gradient = centeredY / centeredX;
            double angle = ((centeredX<0) ? (Math.toDegrees(Math.atan(gradient)) + 180) : (centeredY < 0) ? 360+Math.toDegrees(Math.atan(gradient)) : Math.toDegrees(Math.atan(gradient)));
            if(angle > 360){
                angle -= 360;
            }

            System.out.println("X: " + centeredX + " Y: " + centeredY + " Angle: " + angle);
            int i = 0;
            for(ModMenuButton buttons : getActiveMenu().getButtons()){
                int requiredAngleMin = ((360/buttonCount))*i;
                int requiredAngleMax = (i != buttonCount-1) ? ((360/buttonCount)*(i+1)): 360;
                if((angle < (requiredAngleMax)) && angle >= (requiredAngleMin)){
                    if(buttons instanceof ModMenuButtonMenu){
                        ModMenuButtonMenu clickedButtonVerType = (ModMenuButtonMenu) buttons;
                        clickedButtonVerType.onClick();
                    } else if(buttons instanceof ModMenuButtonMod){
                        if(pyth <= circleSettingsRadius && !getActiveMenu().getName().equalsIgnoreCase("main")){
                            ModMenuButtonMod clickedButtonVerType = (ModMenuButtonMod) buttons;
                            if(clickedButtonVerType.getMod().getModSettings().isEnabled()) {
                                activeMenu = null;
                                this.mc.displayGuiScreen(new GuiModSettings(clickedButtonVerType.getMod().getModSettings()));
                            }
                        } else {
                            ModMenuButtonMod clickedButtonVerType = (ModMenuButtonMod) buttons;
                            clickedButtonVerType.onClick();
                        }
                    }
                }
                i++;
            }
        }
    }

    @Override
    public boolean doesGuiPauseGame() {
        return true;
    }


    public static void setActiveMenu(ModMenu activeMenu) {
        GUIModMenu.activeMenu = activeMenu;
    }

    public static ModMenu getActiveMenu() {
        return activeMenu;
    }

    public static class ModMenuButton {
        String title;
        ModMenuButtonType type;

        public ModMenuButton(ModMenuButtonType type, String title) {
            this.type = type;
            this.title = title;
        }
        public ModMenuButtonType getType() {
            return type;
        }
        public String getTitle() {
            return title;
        }
    }

    public static class ModMenuButtonMod extends ModMenuButton {
        Mod mod;

        public ModMenuButtonMod(String title, Mod mod) {
            super(ModMenuButtonType.Mod, title);

            this.mod = mod;
        }

        public void onClick() {
            mod.setEnabled(!mod.isEnabled());
        }

        public Mod getMod() {
            return mod;
        }
    }

    public static class ModMenuButtonMenu extends ModMenuButton {
        ModMenu menu;

        public ModMenuButtonMenu(String title, ModMenu menu) {
            super(ModMenuButtonType.Menu, title);

            this.menu = menu;
        }

        public void onClick() {
            GUIModMenu.setActiveMenu(menu);
        }
    }

    public static class ModMenu {
        ArrayList<ModMenuButton> buttons;
        String name;
        String backMenu;

        public ModMenu(String name, ArrayList<ModMenuButton> menuButtons, String backMenu) {
            this.name = name;
            this.buttons = menuButtons;
            this.backMenu = backMenu;
        }

        public String getName() {
            return name;
        }

        public ArrayList<ModMenuButton> getButtons() {
            return buttons;
        }

        public String getBackMenu() {
            return backMenu;
        }
    }

    public enum ModMenuButtonType {
        Mod, Menu, Settings
    }

}
