/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GameFrame;

import java.awt.Rectangle;

/**
 *
 * @author geiver.botello
 */
public abstract class Movil {
    public int x,y;
    public Rectangle rect;

    public Movil() {
        rect = new Rectangle();
    }
    

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        this.y = y;
    }

    public Rectangle getRect() {
        return rect;
    }

    public void setRect(Rectangle rect) {
        this.rect = rect;
    }
    
    
    
}
